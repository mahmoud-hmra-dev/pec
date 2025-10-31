<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\UserRequest;
use App\Models\Nurse;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class NurseController extends Controller{

    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_NURSES);
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
        $items = User::with(['nurse','country'])->role(RoleEnum::NURSE)->select('users.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    return '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>
                            <a class="delete btn btn-xs btn-dark mr-1" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                })
                ->make(true);
        }
        return view('dashboard.nurses.index');
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
        $model->fill($request->validated());
        if($request->has('image')){
            $model->image = $this->storeFile($request->image,'users');
        }
        $model->save();
        $model->assignRole(RoleEnum::NURSE);
        Nurse::create(['id'=>$model->id]);
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
        $image = $model->image;
        if($request->has('image')){
            $image = $this->updateFile($request->image,$model->image,'users');
        }
        $model->update($request->validated()+['image'=>$image]);
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
