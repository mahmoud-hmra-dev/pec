<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\DrugRequest;
use App\Models\Client;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Drug;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DrugController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_DRUGS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_DRUGS)->only('index');
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
        $clients  = Client::all();
        $items = Drug::with(['client','client.user'])->select('drugs.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_DRUGS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.drugs.index',['clients'=>$clients]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param DrugRequest $request
    * @return RedirectResponse
    */
    public function store(DrugRequest $request)
    {
        $model = new Drug();
        $model->fill($request->validated());
        $model->save();
        return response()->json(['success' => true,'message'=>"Drug Added Successfully"],200);
    }



    /**
    * Update the specified resource in storage.
    *
    * @param  DrugRequest $request
    * @param  int  $id
    * @return RedirectResponse
    */
    public function update(DrugRequest $request, $id)
    {
        $model = Drug::findOrFail($id);
        $model->update($request->validated());
        return response()->json(['success' => true,'message'=>"Drug Updated Successfully"],200);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return JsonResponse
    */
    public function destroy($id)
    {
        $model = Drug::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
