<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'contact_role',
        'name',
        'email',
        'title',
        'custom_title',
        'phone',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getResolvedTitleAttribute(): ?string
    {
        return $this->custom_title ?: $this->title;
    }
}
