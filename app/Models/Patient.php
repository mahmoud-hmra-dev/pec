<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Patient
 * @package App\Models
 * @mixin Builder
 */

class Patient extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = [];


    public function user(){
        return $this->belongsTo(User::class,'id');
    }

    public function hospital(){
        return $this->belongsTo(Hospital::class);
    }
    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class);
    }




    public function consents(){
        return $this->hasMany(Consent::class);
    }

    public function sub_program_patients(){
        return $this->hasMany(SubProgramPatient::class);
    }

    public function patient_doctors(){
        return $this->hasMany(PatientDoctor::class);
    }
    public function patient_documents(){
        return $this->hasMany(PatientDocument::class);
    }
}
