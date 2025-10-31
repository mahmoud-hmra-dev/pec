<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicianData extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'visit_history_id',
        'notes',
        'other_medications'
    ];

    public function visit_history()
    {
        return $this->belongsTo(VisitHistory::class, 'visit_history_id');
    }
}
