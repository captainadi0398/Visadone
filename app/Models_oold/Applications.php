<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;
    // protected $dates = ['deleted_at'];
    protected $table = "applicants_data";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'visa_id',
        'code',
        'passport_number',
        'surname',
        'firstname',
        'nationality',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'authority',
        'date_of_issue',
        'date_of_expire',
        'MRZ',
        'application_JSON',
        'key_field',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
