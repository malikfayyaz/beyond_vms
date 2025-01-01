<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormBuilder extends Model
{
    use HasFactory;

    protected $table = 'form_builder'; // Table name in the database

    // Mass assignable fields
    protected $fillable = [
        'type',
        'data',
        'status',
        'created_by',
        'created_by_portal',
    ];

    
}
