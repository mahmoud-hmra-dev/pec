<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
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

class UserController extends Controller{

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
        $items = User::with(['country'])
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Sub Admin']);
            })
            ->select('users.*');

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_USERS)) {
                        $actions .= '<a class="view btn btn-xs btn-success" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>
                            <a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    return $actions;
                })
                ->addColumn('roleNames', function($row){
                    return  implode('-', $row->getRoleNames()->toArray()) ?? '';
                })
                ->rawColumns(['action' , 'roleNames'])
                ->make(true);
        }

        return view('dashboard.users.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        $model = new User();
        $model->fill($request->only(['first_name',
            'last_name',
            'phone',
            'email',
            'password',
            'personal_email',
            'country_id',
            'city',
            'address',
        ]));
        $model->save();
        $model->assignRole($request->role);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $model = User::findOrFail($id);
        $model->update($request->only(['first_name',
            'last_name',
            'phone',
            'email',
            'password',
            'personal_email',
            'country_id',
            'city',
            'address',
        ]));
        if($request->role){
            $model->syncRoles($request->role);
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
        $model = User::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

    /**
     * Remove the specified image of category from storage and the database
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function deleteImage($id)
    {
        $model = User::findOrFail($id);
        if($this->deleteFile(str_replace(asset('storage/'),'',$model->image))) {
            $model->image = null;
            $model->save();
            return response()->json(['message' => 'Successfully Deleted!']);
        }
        return response()->json(['message' => 'Delete Failed!']);
    }
}
