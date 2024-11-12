<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity_log';
    protected $fillable = ['description', 'log_name', 'subject_type', 'subject_id', 'causer_type', 'causer_id'];
public function createdBy()
    {
        return $this->belongsTo(User::class, 'causer_id', 'id');
    }
public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'subject_id' , 'id');
    }
}
