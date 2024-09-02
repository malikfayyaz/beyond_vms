<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingCategory extends Model
{
    protected $guarded = [ 
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class, 'category_id');
    }
}
