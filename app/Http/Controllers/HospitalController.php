<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\HospitalRequest;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class HospitalController extends Controller{



    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_HOSPITALS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_HOSPITALS)->only('index');

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
        $items = Hospital::with('country')->select('hospitals.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_HOSPITALS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.hospitals.index');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param HospitalRequest $request
    * @return JsonResponse
    */
    public function store(HospitalRequest $request)
    {
        $model = new Hospital();
        $model->fill($request->validated());
        $model->save();
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  HospitalRequest $request
    * @param  int  $id
    * @return JsonResponse
    */
    public function update(HospitalRequest $request, $id)
    {
        $model = Hospital::findOrFail($id);
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
        $model = Hospital::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
