<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramFormEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_form_field_id',
        'sub_program_patient_id',
        'value',
    ];

    public function field()
    {
        return $this->belongsTo(ProgramFormField::class, 'program_form_field_id');
    }

    public function patient()
    {
        return $this->belongsTo(SubProgramPatient::class, 'sub_program_patient_id');
    }
}
