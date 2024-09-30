<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesInterview extends Model
{
    use HasFactory;

    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
    }

    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }

    public function duration() {
        return $this->belongsTo(Setting::class, 'interview_duration', 'id');
    }
    
    public function timezone() {
        return $this->belongsTo(Setting::class, 'time_zone', 'id');
    }

    public function interviewtype() {
        return $this->belongsTo(Setting::class, 'interview_type', 'id');
    }
    
    public function location() {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
