<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nurse extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'id');
    }

    public function visit_schedules(){
        return $this->hasMany(VisitSchedule::class);
    }

    public function visits(){
        return $this->hasMany(Visit::class);
    }
}
