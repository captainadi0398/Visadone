<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;
    // protected $dates = ['deleted_at'];
    protected $table = "visa_data";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'user_id',
        'branch_id',
        'nationality',
        'destination',
        'travel_date_from',
        'travel_date_to',
        'visa_type_selected',
        'visa_offer',
        'offer_id',
        'visa_fees',
        'visa_status',
        'submitted_at',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    // protected $hidden = ['password', 'remember_token', 'api_token'];
}
