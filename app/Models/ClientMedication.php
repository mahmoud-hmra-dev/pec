<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientMedication extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'api_name',
        'drug_initial',
        'medication_type_id',
    ];
}
