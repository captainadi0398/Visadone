<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    // protected $dates = ['deleted_at'];
    protected $table = "branches";
    public $timestamp = false;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'description',
        'note',

    ];

}
