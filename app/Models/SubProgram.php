<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubProgram
 * @package App\Models
 * @mixin Builder
 */
class SubProgram extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];
    protected $fillable = [
        'name'                         ,
        'program_id'                    ,
        'country_id'                    ,
        'drug_id'                               ,
        'type'                         ,
        'target_number_of_patients'     ,
        'eligible'              ,
        'has_calls'             ,
        'has_visits'            ,
        'is_follow_program_date',
        'start_date'            ,
        'finish_date'              ,
        'treatment_duration'       ,
        'program_initial'       ,
        'visit_every_day'               ,
        'call_every_day'                ,

        'has_FOC',
        'cycle_period',
        'cycle_number',
        'cycle_reminder_at',
    ];


    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function drug(){
        return $this->belongsTo(Drug::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function sub_program_patients(){
        return $this->hasMany(SubProgramPatient::class);
    }
    public function country_services_provider(){
        return $this->hasMany(CountryServiceProvider::class);
    }

}
