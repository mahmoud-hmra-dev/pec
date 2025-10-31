<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\DoctorRequest;
use App\Http\Requests\HospitalRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller{



    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_DOCTORS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_DOCTORS)->only('index');
    }

    /**
    * Display a listing of the resource.
    *
    * @param Request $request
    * @return Application|Factory|View|JsonResponse
    * @throws Exception
    */
    public function index(Request $request)
    {
        $items = Doctor::select('doctors.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_DOCTORS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.doctors.index');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param DoctorRequest $request
    * @return JsonResponse
    */
    public function store(DoctorRequest $request)
    {
        $model = new Doctor();
        $model->fill($request->validated());
        $model->save();
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  DoctorRequest $request
    * @param  int  $id
    * @return JsonResponse
    */
    public function update(DoctorRequest $request, $id)
    {
        $model = Doctor::findOrFail($id);
        $model->update($request->validated());
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return JsonResponse
    */
    public function destroy($id)
    {
        $model = Doctor::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
