<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = "tax_data";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'country_name',
        'country_code',
        'tax_name',
        'tax_percentage',
        'created_at',
        'updated_at',

    ];

}
