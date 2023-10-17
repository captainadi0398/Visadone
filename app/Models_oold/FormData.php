<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    use HasFactory;
    protected $table = "forms_data";


    protected $fillable = [
        'id', 'country', 'conditional_filed', 'field_name', 'is_dropdown', 'is_required', 'default_values', 'placeholder_value'
    ];
}
