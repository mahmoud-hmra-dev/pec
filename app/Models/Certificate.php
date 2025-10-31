<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'url',
        'service_provider_id'
    ];

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }
}
