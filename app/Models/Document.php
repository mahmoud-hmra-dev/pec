<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = [];
    public $fillable =[
        'client_id',
        'document_type_id',
        'name',
        'description'
    ];

    public function type(){
        return $this->belongsTo(DocumentType::class,'document_type_id');
    }


    public function client(){
        return $this->belongsTo(Client::class);
    }
}
