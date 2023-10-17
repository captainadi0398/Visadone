<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];
    protected $table = "agency_data";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'agency_name',
        'branch_name',
        'country',
        'contact_number',
        'country_ISO',
        'currency',
        'address_line',
        'contact_number_branch',
        'email',
        'status',
        'agent_role',
        'branch_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
