<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDocument extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'document_type_id',
        'client_id'
    ];

    public function type(){
        return $this->belongsTo(DocumentType::class,'document_type_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
