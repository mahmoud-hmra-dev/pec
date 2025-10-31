<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientSafetyReport extends Model
{
    use HasFactory , SoftDeletes;
    public $guarded = [];
    public function sub_program_patient()
    {
        return $this->belongsTo(SubProgramPatient::class);
    }
}
