<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProviderType extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'service_type_id',
        'service_provider_id',
    ];

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }
    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id');
    }
    public function country_services_provider()
    {
        return $this->hasMany(CountryServiceProvider::class, 'service_provider_type_id');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class, 'service_provider_type_id');
    }
}
