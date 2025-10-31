<?php

namespace App\Http\Responses;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function toResponse($request)
    {
        if(auth()->user()->hasRole(RoleEnum::ADMIN))
            return redirect(RouteServiceProvider::ADMIN_PAGE);
        if(auth()->user()->hasRole(RoleEnum::PATIENT))
            return redirect(RouteServiceProvider::PATIENT_PAGE);
        return redirect(RouteServiceProvider::HOME);
    }
}
