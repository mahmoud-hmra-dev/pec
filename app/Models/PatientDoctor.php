<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientDoctor extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'isActive',
        'patient_id',
        'doctor_id'
    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
