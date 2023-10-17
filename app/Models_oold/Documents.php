<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];
    protected $table = "document_data";
    public $timestamp=false;

    protected $fillable = [
        'id', 'visa_id', 'document_name', 'documents_path', 'documents_type', 'status', 'rule_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    // protected $hidden = ['password', 'remember_token', 'api_token'];
}
