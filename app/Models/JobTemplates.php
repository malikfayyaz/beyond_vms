<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTemplates extends Model
{
    use SoftDeletes;
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function templateratecard()
    {
        return $this->hasMany(TemplateRatecard::class, 'template_id');
    }
    public function category()
    {
        return $this->belongsTo(Setting::class, 'cat_id', 'id');
    }
    public function profileWorkerType()
    {
        return $this->belongsTo(Setting::class, 'profile_worker_type_id', 'id');
    }

}
