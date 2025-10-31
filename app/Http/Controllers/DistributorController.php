<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\DistributorRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Models\Distributor;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Program;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DistributorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_Distributors)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_Distributors)->only('index');
    }


    public function index(Request $request,$program_id)
    {
        $items = Distributor::where('program_id',$program_id)->with(['program','country'])->select('distributors.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_Distributors)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.programs.distributors.index',['program_id'=>$program_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(DistributorRequest $request,$program_id)
    {
        $model = Program::findOrFail($program_id);
        $model->distributors()->create([
            'contract_person'=> $request->contract_person,
            'name'=> $request->name,
            'country_id'=> $request->country_id,
            'email'=> $request->email,
            'phone'=> $request->phone,
        ]);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(DistributorRequest $request,$program_id, $id)
    {
        $model = Distributor::findOrFail($id);
        $model->update([
            'contract_person'=> $request->contract_person,
            'name'=> $request->name,
            'country_id'=> $request->country_id,
            'email'=> $request->email,
            'phone'=> $request->phone,
        ]);


        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($program_id, $id)
    {
        $model = Distributor::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

}
