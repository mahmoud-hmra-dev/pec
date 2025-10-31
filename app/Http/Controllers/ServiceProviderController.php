<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ServiceProviderRequest;
use App\Http\Requests\UserRequest;
use App\Models\Certificate;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ServiceProviderController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_ServiceProvider)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_ServiceProvider)->only('index');
    }

    use FileHandler;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $items = ServiceProvider::with(['user','user.country','country','service_types','certificates'])->select('service_providers.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_ServiceProvider)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_ServiceProvider)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>';
                    }
                    return $actions;
                })
                /*<a class=" btn btn-xs btn-success" style="color:#fff" href="'.route('service-providers.certificates.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Certificates</a>
                            <a class=" btn btn-xs btn-success" style="color:#fff" href="'.route('service-providers.service_provider_types.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Service types</a>*/
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.service-providers.index');
    }
    public function create(){
        $programs = Program::all();

        return view('dashboard.service-providers.create',compact(['programs']));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(ServiceProviderRequest $request)
    {
        $user = new User();
        $user->fill([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>$request->password,
            'personal_email'=>$request->personal_email,
            'country_id'=>$request->country_id,
            'city'=>$request->city,
            'address'=>$request->address,
        ]);
        $user->save();

        $model = ServiceProvider::create([
            'user_id'             => $user->id,
            'contract_type'       => $request->contract_type,
            'contract_rate_price' => $request->contract_rate_price,
            'contract_rate_price_per' => $request->contract_rate_price_per,
            'city'                => $request->city,
            'country_id'          => $request->country_id,
        ]);

        if($request->hasFile('attach_cv')){
            $model->attach_cv = $this->storeFile($request->attach_cv,'service-providers',false);
        }
        if($request->hasFile('attach_contract')){
            $model->attach_contract = $this->storeFile($request->attach_contract,'service-providers',false);
        }
        $model->save();

        if(!empty($request->certificates_list)) {
            foreach ($request->certificates_list as $key => $item) {
                if ($request->hasFile('certificates_list.'.$key.'.certificate')) {
                    $certificatePath = $this->storeFile($request->file('certificates_list.'.$key.'.certificate'), 'service-providers', false);
                    $model->certificates()->create([
                        'url' => $certificatePath
                    ]);
                }
            }
        }
        if(!empty($request->service_types)) {

            foreach ($request->service_types as $item) {
                $service_provider_type = $model->service_provider_types()->where('service_type_id',$item)->first();

                if(!$service_provider_type){
                    $model->service_provider_types()->create([
                        'service_type_id' => $item
                    ]);
                }
            }
        }

        $roles = $model->service_types()->pluck('name')->toArray();
        if(!empty($roles)){
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }



        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    public function edit($id){

        $serviceProvider = ServiceProvider::findOrFail($id);
        $programs = Program::all();

        return view('dashboard.service-providers.edit',compact(['serviceProvider','programs']));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ServiceProviderRequest $request, $id)
    {
        $service_types_deleted = [];
        $service_types_remaining =  [];
        $message = "Updated Successfully";

        $model = ServiceProvider::findOrFail($id);

        if($request->has('attach_cv')){
            $attach_cv = $this->updateFile($request->attach_cv,$model->attach_cv,'service-providers',false);
        }
        if($request->has('attach_contract')){
            $attach_contract = $this->updateFile($request->attach_contract,$model->attach_contract,'service-providers',false);
        }
        $user = User::findOrFail($model->user_id);

        $user->update([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>$request->password ? $request->password : $user->password,
            'personal_email'=>$request->personal_email,
            'country_id'=>$request->country_id,
            'city'=>$request->city,
            'address'=>$request->address,
        ]);

        $model->update([
            'contract_type'       => $request->contract_type,
            'contract_rate_price' => $request->contract_rate_price,
            'contract_rate_price_per' => $request->contract_rate_price_per,
            'city'                => $request->city,
            'country_id'          => $request->country_id,
            'attach_cv'           =>$attach_cv ?? $model->attach_cv,
            'attach_contract'     =>$attach_contract ?? $model->attach_contract,
        ]);

        if(!empty($request->certificates_list)) {
            Certificate::where('service_provider_id', $model->id)
                ->whereNotIn('id', array_column($request->certificates_list, 'id'))
                ->delete();
            foreach ($request->certificates_list as $key => $item) {
                if (isset($item['id']) and $request->hasFile('certificates_list.'.$key.'.certificate')) {
                    $certificate = Certificate::where('id',$item['id'])->first();
                    $certificatePath = $this->updateFile($request->file('certificates_list.'.$key.'.certificate'),$certificate->url,'service-providers',false);
                    $certificate->update([
                        'url'  => $certificatePath,
                    ]);
                } elseif( isset($item['certificate']) and $request->hasFile('certificates_list.'.$key.'.certificate')) {
                    $certificatePath = $this->storeFile($request->file('certificates_list.'.$key.'.certificate'), 'service-providers', false);
                    $model->certificates()->create([
                        'url'  => $certificatePath,
                    ]);
                }
            }
        } else {
            $model->certificates()->delete();
        }

        if(!empty($request->service_types)) {
            $service_types = ServiceProviderType::where('service_provider_id', $model->id)
                ->whereNotIn('service_type_id', $request->service_types)
                ->get();

            foreach ($service_types as $service_type) {
                if ($service_type->country_services_provider()->count() <= 0 and $service_type->visits()->count() <= 0) {
                    $service_type->delete();
                    $service_types_deleted[] = $service_type;
                } else {
                    $service_types_remaining[] = $service_type;
                }
            }

            foreach ($request->service_types as $key => $item) {
                $service_provider_type = $model->service_provider_types()->where('service_type_id',$item)->first();

                if(!$service_provider_type){
                    $model->service_provider_types()->create([
                        'service_type_id' => $item
                    ]);
                }
            }

        } else {
            $service_types = ServiceProviderType::where('service_provider_id', $model->id)
                ->get();
            foreach ($service_types as $service_type) {
                if ($service_type->country_services_provider()->count() <= 0 and $service_type->visits()->count() <= 0) {
                    $service_type->delete();
                    $service_types_deleted[] = $service_type;
                } else {
                    $service_types_remaining[] = $service_type;
                }
            }
        }

        $roles = $model->service_types()->pluck('name')->toArray();
        if(!empty($roles)){
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        $remaining_message = '';
        if (!empty($service_types_remaining)) {
            $remaining_message = "Remaining Service Types: ";
            foreach ($service_types_remaining as $service_type) {
                $remaining_message .= $service_type->service_type->name . ', ';
            }
            $remaining_message = rtrim($remaining_message, ', ');
        }

        if (!empty($service_types_deleted)) {
            $deleted_message = "Deleted Service Types: ";
            foreach ($service_types_deleted as $service_type) {
                $deleted_message .= $service_type->service_type->name . ', ';
            }
            $deleted_message = rtrim($deleted_message, ', ');

            $message .= " $deleted_message. $remaining_message";
        } else {
            $message .= " $remaining_message";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $model = ServiceProvider::findOrFail($id);
        if($model->delete()){
            $this->deleteFile($model->attach_cv);
            $this->deleteFile($model->attach_contract);
        }
        return response()->json(['message' => 'Successfully Deleted!']);
    }


}
