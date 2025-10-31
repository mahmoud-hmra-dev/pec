<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Specialty
 * @package App\Models
 * @mixin Builder
 */
class Specialty extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = ['id'];

    public function physicians(){
        return $this->hasMany(Physician::class);
    }

}
