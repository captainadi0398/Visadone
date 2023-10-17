<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];
    protected $table = "users";
    public $timestamp=false;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile_number', 'user_type', 'password', 'api_token', 'rember_tokem', 'Country_of_residences', 'country', 'branch_id', 'status'
    ];

    protected $hidden = ['password', 'remember_token', 'api_token'];

    public function hasPermission($permission)
{
    // Assuming you have a many-to-many relationship between User and Role
    return $this->roles->flatMap->permissions->pluck('name')->contains($permission);
}

}
