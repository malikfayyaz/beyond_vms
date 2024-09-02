<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // use HasFactory;
    protected $guarded = [ 
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function settingcategory()
    {
        return $this->belongsTo(SettingCategory::class, 'category_id');
    }
}
