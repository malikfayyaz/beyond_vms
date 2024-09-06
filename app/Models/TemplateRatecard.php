<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateRatecard extends Model
{
    // use SoftDeletes;
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function jobtemplates()
    {
        return $this->belongsTo(JobTemplates::class, 'template_id');
    }
    public function jobLevel()
    {
        return $this->belongsTo(Setting::class, 'level_id', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(Setting::class, 'currency_id', 'id');
    }
}
