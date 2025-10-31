<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientCountryProvider extends Model
{
    use HasFactory , SoftDeletes;
    public $guarded = ['id'];
    public function country_service_provider()
    {
        return $this->belongsTo(CountryServiceProvider::class, 'country_service_provider_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function visits(){
        return $this->hasMany(Visit::class);
    }
}
