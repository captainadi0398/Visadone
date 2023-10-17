<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    protected $dates = ['deleted_at'];
    protected $table = "agent_data";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'branch_id',
        'country',
        'contact_number_agency',
        'agency_id',
        'agent_firstname',
        'agent_lastname',
        'Email',
        'contact_number',
        'password',
        'status',
        'role',

    ];

}
