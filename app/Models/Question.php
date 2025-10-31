<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    protected $fillable = [
        'question',
        'question_type_id',
        'category_id',
        'sub_program_id'
    ];


    public function sub_program(){
        return $this->belongsTo(SubProgram::class);
    }

    public function type(){
        return $this->belongsTo(QuestionType::class,'question_type_id');
    }

    public function category(){
        return $this->belongsTo(QuestionCategory::class);
    }

    public function choices(){
        return $this->hasMany(Choice::class);
    }
}
