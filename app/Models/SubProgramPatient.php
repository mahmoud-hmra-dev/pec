<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubProgramPatient extends Model
{
    use HasFactory , SoftDeletes;
    public $guarded = [];

    public function patient_country_providers (){
        return $this->hasMany(PatientCountryProvider::class);
    }
    public function visits(){
        return $this->hasMany(Visit::class);
    }
    public function foc_visits(){
        return $this->hasMany(FOCVisit::class);
    }
    public function sub_program(){
        return $this->belongsTo(SubProgram::class);
    }
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
    public function saftey_reports(){
        return $this->hasMany(PatientSafetyReport::class);
    }
}
