<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaOffers extends Model
{
    use HasFactory;

    protected $table = "visa_offer_data";
    public $timestamp=false;

    protected $fillable = [
        'id', 'nationality', 'destination', 'visa_type', 'visa_category', 'entry_fees', 'visa_description', 'processing_time', 'visa_validity', 'stay_validity', 'category', 'duration', 'description', 'base_rate_adult', 'base_rate_child', 'base_rate_Infant', 'govt_fees_adult', 'govt_fees_child', 'govt_fees_infant', 'currency_value', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    // protected $hidden = ['password', 'remember_token', 'api_token'];
}
