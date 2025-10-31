<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builders\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class ProgramFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'label',
        'field_key',
        'field_type',
        'is_required',
        'display_order',
        'options',
        'help_text',
    ];

    protected $casts = [
        'is_required' => 'bool',
        'options' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function entries()
    {
        return $this->hasMany(ProgramFormEntry::class);
    }
}
