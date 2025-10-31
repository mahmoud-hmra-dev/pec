<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FOCVisit extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function sub_program_patient(){
        return $this->belongsTo(SubProgramPatient::class);
    }
    public function sub_program(){
        return $this->belongsTo(SubProgram::class);
    }
    public function service_provider_type(){
        return $this->belongsTo(ServiceProviderType::class);
    }

}
