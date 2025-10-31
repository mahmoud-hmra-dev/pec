<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Consent;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientConsentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_PATIENTS);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $consents = Consent::with(['patient','physician','physician.user','program','client'])->select('consents.*');
        if($request->ajax()){
            return  DataTables::eloquent($consents)
                ->addColumn('action', function ($consents) {
                    return '<a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                })
                ->make(true);
        }

        return view('dashboard.consent.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Consent::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
