<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'organization', 'profile_image', 'business_name' // Add this if you're updating the user_name field
    ];
}
