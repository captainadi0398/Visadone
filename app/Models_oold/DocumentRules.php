<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRules extends Model
{
    use HasFactory;
    // protected $dates = ['deleted_at'];
    protected $table = "document_rule_data";
    public $timestamp=false;

    protected $fillable = [
        'id','offer_id','document_type', 'document_description', 'document_needed', 'document_name', 'nationality', 'destination', 'visa_type', 'created_at', 'updated_at', 'deleted_at'
    ];


}
