<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consent extends Model
{
    use HasFactory,SoftDeletes;
    public $guarded = [];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function program(){
        return $this->belongsTo(Program::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function physician(){
        return $this->belongsTo(Physician::class);
    }
}
