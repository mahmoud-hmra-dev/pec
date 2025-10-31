<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
