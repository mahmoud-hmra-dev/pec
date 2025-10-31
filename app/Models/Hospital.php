<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hospital
 * @package App\Models
 * @mixin Builder
 */
class Hospital extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function patients(){
        return $this->hasMany(Patient::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
