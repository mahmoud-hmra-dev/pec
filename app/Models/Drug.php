<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* @property string name
* @property integer program_id
* @property string api_name
* @property string drug_initial
* @property string drug_id
* @mixin Builder
*/
class Drug extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function program(){
        return $this->belongsTo(Client::class);
    }
    public function sub_programs(){
        return $this->hasMany(SubProgram::class);
    }
}
