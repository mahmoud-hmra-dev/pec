<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\SpecialtyRequest;
use Illuminate\Http\Request;
use App\Models\Specialty;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class SpecialtyController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_SPECIALTY);
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
        $items = Specialty::select('specialties.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    return '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                })
                ->make(true);
        }

        return view('dashboard.specialties.index');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param SpecialtyRequest $request
    * @return JsonResponse
    */
    public function store(SpecialtyRequest $request)
    {
        $model = new Specialty();
        $model->fill($request->validated());
        $model->save();
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  SpecialtyRequest $request
    * @param  int  $id
    * @return JsonResponse
    */
    public function update(SpecialtyRequest $request, $id)
    {
        $model = Specialty::findOrFail($id);
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
        $model = Specialty::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
