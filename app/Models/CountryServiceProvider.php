<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_provider_type_id',
        'country_id',
        'sub_program_id',
    ];

    public function sub_program()
    {
        return $this->belongsTo(SubProgram::class, 'sub_program_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function service_provider_type()
    {
        return $this->belongsTo(ServiceProviderType::class, 'service_provider_type_id');
    }
}
