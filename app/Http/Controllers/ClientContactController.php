<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ClientContactRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\SubProgramPatientRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\CountryServiceProvider;
use App\Models\Drug;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\PatientCountryProvider;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\SubProgramPatient;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_CLIENTS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_CLIENTS)->only('index');

    }


    public function index(Request $request , $client_id)
    {
        $items = ClientContact::with(['client','client.user','client.user.country'])
            ->where('client_id', $client_id)
            ->select('client_contacts.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item)  {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_CLIENTS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a><a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.clients.contacts.index',['client_id'=>$client_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(ClientContactRequest $request , $client_id)
    {
        $client = Client::find($client_id);

        $client_contact = $client->contacts()->create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>$request->role,
        ]);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PatientRequest $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(ClientContactRequest $request , $client_id, $id)
    {
        $client_contact = ClientContact::find($id);
        $client_contact->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>$request->role,
        ]);

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($client_id,$id)
    {
        $model = ClientContact::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

}
