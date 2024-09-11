<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'description',
        'profile_image',
        'username', // Add this if you're updating the user_name field
    ];
    use HasFactory;
}
