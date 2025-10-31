<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Enums\RoleEnum;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function (){
    return redirect(\route('dashboard'));
})->name('home');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::prefix('patient')->group(function(){
    Route::resource('consent',Controllers\PatientConsentController::class)->middleware(['auth','role:'.RoleEnum::PATIENT]);

    Route::get('/otp/login',[Controllers\AuthController::class,'login'])->name('otp.login');
    Route::post('/otp/generate',[Controllers\AuthController::class,'generate'])->name('otp.generate');
    Route::get('/otp/verification/{uuid}',[Controllers\AuthController::class,'verification'])->name('otp.verification');
    Route::post('/otp/login',[Controllers\AuthController::class,'loginWithOtp'])->name('otp.getlogin');

});


