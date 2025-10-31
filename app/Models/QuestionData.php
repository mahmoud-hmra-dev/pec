<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionData extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'visit_id',
        'question_id',
        'content'
    ];
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
