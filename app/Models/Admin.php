<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\Country;

class Admin extends Model
{
    use HasFactory;

     protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'role_id', 'country', 'status', 'profile_image','member_access'
    ];


    public function role()
    {
        return $this->belongsTo(Role::class, 'member_access', 'id'); // assuming `member_access` is the foreign key for roles
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }
}
