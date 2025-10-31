<?php

namespace App\Models;

use App\Scopes\ActivationScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
* @property string name
* @property string phone_extension
* @property boolean is_active
*/

class Country extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function users(){
        return $this->hasMany(User::class,'country_id');
    }

    public function hospitals(){
        return $this->hasMany(Hospital::class,'country_id');
    }

    public function sub_programs(){
        return $this->hasMany(SubProgram::class);
    }

    protected static function booted(){
        // get active records (is_active = 1) for all users but admin
        static::addGlobalScope(new ActivationScope);
    }
}
