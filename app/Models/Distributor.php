<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'program_id',
        'contract_person',
        'country_id',
        'name',
        'email',
        'phone'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
