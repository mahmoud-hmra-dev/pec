<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'contract_type',
        'contract_rate_price',
        'contract_rate_price_per',
        'attach_contract',
        'attach_cv',
        'city',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'service_provider_id');
    }

    public function service_provider_types()
    {
        return $this->hasMany(ServiceProviderType::class, 'service_provider_id');
    }
    public function service_types()
    {
        return $this->hasManyThrough(ServiceType::class, ServiceProviderType::class, 'service_provider_id', 'id', 'id', 'service_type_id'
        );
    }
}
