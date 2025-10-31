<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function service_provider_type(){
        return $this->belongsTo(ServiceProviderType::class,'service_provider_type_id');
    }

    public function sub_programs(){
        return $this->hasMany(SubProgram::class);
    }
    public function program_drugs(){
        return $this->hasMany(ProgramDrug::class);
    }
    public function program_countries(){
        return $this->hasMany(ProgramCountry::class);
    }
    public function distributors(){
        return $this->hasMany(Distributor::class);
    }

    public function contacts()
    {
        return $this->hasMany(ProgramContact::class);
    }

    public function formFields()
    {
        return $this->hasMany(ProgramFormField::class)->orderBy('display_order');
    }
}
