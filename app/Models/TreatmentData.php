<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentData extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'visit_history_id',
        'current_dose_reached',
        'dose_escalation',
        'dose_date',
        'side_effects'
    ];

    public function visit_history()
    {
        return $this->belongsTo(VisitHistory::class, 'visit_history_id');
    }
}
