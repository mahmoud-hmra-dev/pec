<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramCountry extends Model
{
    use HasFactory,SoftDeletes;
    public $guarded = ['id'];

    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
