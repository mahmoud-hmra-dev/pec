<?php

namespace App\Models;

use App\Notifications\ResetPasswordEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens , HasFactory, HasRoles , Notifiable,SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_path'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullPathAttribute(){
        return $this->image != null ? asset('storage/'.$this->image) : null;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordEmailNotification($token));
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }


    public function patient(){
        return $this->hasOne(Patient::class,'id');
    }
    public function service_provider()
    {
        return $this->hasOne(ServiceProvider::class, 'user_id');
    }
    public function client(){
        return $this->hasOne(Client::class,'id');
    }

    public function setPasswordAttribute($pass){
        $this->attributes['password'] = Hash::make($pass);
    }

    public function verificationCodes()
    {
        return $this->hasMany(VerificationCode::class , 'user_id' , 'id');
    }

}
