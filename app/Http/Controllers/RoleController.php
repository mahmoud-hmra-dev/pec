<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\UserRequest;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_USERS);
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
        $items = Role::with('permissions')->select('roles.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_USERS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>  ';
                    }
                    return $actions;
                })
                ->rawColumns(['action' ])
                ->make(true);
        }

        return view('dashboard.roles.index',['permissions'=>Permission::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request)
    {
        $role = Role::create([
            "name" => $request->name,
            "guard_name" => "web",
        ]);
        if (!empty($request->permissions)) {
            $role->givePermissionTo($request->permissions);
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
    public function update(RoleRequest $request, $id)
    {
        $model = Role::findOrFail($id);
        $model->update([
            "name" => $request->name,
        ]);
        $model->revokePermissionTo($model->permissions);
        if (!empty($request->permissions)) {
            $model->givePermissionTo($request->permissions);
        }
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
        $model = Role::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
