<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'id');
    }

    public function documents(){
        return $this->hasMany(ClientDocument::class);
    }
    public function documentsWithTypes()
    {
        return $this->documents()->with('type')->get();
    }

    public function drugs(){
        return $this->hasMany(Drug::class);
    }

    public function contacts(){
        return $this->hasMany(ClientContact::class);
    }
}
