<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller{
    use PasswordValidationRules;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('dashboard.users.profile.index');
    }
    public function update(ProfileRequest $request , $id)
    {
        $model = User::findOrFail($id);
        $model->update($request->only(['first_name',
            'last_name',
            'phone',
            'email',
            'personal_email',
            'country_id',
            'city',
            'address',
        ]));
        return redirect()->route('profile.index')->with('success','Profile Updated Successfully');
    }

    public function password(Request $request , $id)
    {
        $model = User::findOrFail($id);
        $data = $request->all();
        $data = $request->all();

        $validator = Validator::make($data, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ]);

        $validator->after(function ($validator) use ($model, $data) {
            if (!Hash::check($data['current_password'], $model->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        });

        $validator->validate();

        $model->forceFill([
            'password' => $data['password'],
        ])->save();

        return redirect()->route('profile.index')->with('success', 'Password changed successfully');
    }
}
