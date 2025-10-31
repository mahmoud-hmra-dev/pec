<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Visit
 * @package App\Models
 * @mixin Builder
 */
class Visit extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function sub_program_patient(){
        return $this->belongsTo(SubProgramPatient::class);
    }


    public function sub_program(){
        return $this->belongsTo(SubProgram::class);
    }
    public function activity_type(){
        return $this->belongsTo(ActivityType::class);
    }
    public function question_data(){
        return $this->hasMany(QuestionData::class);
    }

    public function service_provider_type(){
        return $this->belongsTo(ServiceProviderType::class);
    }

    public function visit_documents(){
        return $this->hasMany(VisitDocument::class);
    }
}
