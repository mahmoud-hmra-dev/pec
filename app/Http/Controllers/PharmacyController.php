<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\CountryRequest;
use App\Http\Requests\PharmacyRequest;
use App\Models\Pharmacy;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;

class PharmacyController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_PHARMACIES)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_PHARMACIES)->only('index');
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
        $items = Pharmacy::with(['country'])->select('pharmacies.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PHARMACIES)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        return view('dashboard.pharmacies.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PharmacyRequest $request
     * @return JsonResponse
     */
    public function store(PharmacyRequest $request)
    {
        $model = new Pharmacy();
        $model->fill($request->validated());
        $model->save();
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PharmacyRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PharmacyRequest $request, $id)
    {
        $model = Pharmacy::findOrFail($id);
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
        $model = Pharmacy::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
