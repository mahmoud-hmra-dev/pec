<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\CertificateRequest;
use App\Http\Requests\ServiceProviderRequest;
use App\Http\Requests\UserRequest;
use App\Models\Certificate;
use App\Models\Program;
use App\Models\ServiceProvider;
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

class CertificateController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_ServiceProvider);
    }

    use FileHandler;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request,$service_provider_id)
    {
        $items = Certificate::where('service_provider_id',$service_provider_id)->with(['service_provider'])->select('certificates.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    return '<a class="edit btn btn-xs btn-primary" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.service-providers.certificates.index',['service_provider_id'=>$service_provider_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(CertificateRequest $request,$service_provider_id)
    {
        $model = ServiceProvider::findOrFail($service_provider_id);
        if ($request->hasFile('certificate')) {
            $certificatePath = $this->storeFile($request->certificate,'service-providers',false);
            $model->certificates()->create([
                'url' => $certificatePath
            ]);
        }

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CertificateRequest $request, $service_provider_id, $id)
    {
        $model = Certificate::findOrFail($id);

        if ($request->hasFile('certificate')) {
            $attach_certificate = $this->updateFile($request->certificate,$model->certificate,'service-providers',false);
            $model->update([
                'url' => $attach_certificate
            ]);
        }
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($service_provider_id, $id)
    {
        $model = Certificate::findOrFail($id);
        if($model->delete()){
            $this->deleteFile($model->certificate);
        }
        return response()->json(['message' => 'Successfully Deleted!']);
    }

}
