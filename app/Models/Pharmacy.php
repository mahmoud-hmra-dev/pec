<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pharmacy
 * @package App\Models
 * @mixin Builder
 */
class Pharmacy extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
