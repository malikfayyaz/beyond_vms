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
    
    public function submission()
    {
        return $this->belongsTo(CareerOpportunitySubmission::class, 'submission_id' , 'id');
    }
    public function offer()
    {
        return $this->hasOne(CareerOpportunitiesOffer::class, 'submission_id' , 'submission_id');
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
    
    public function reasonRejection() {
        return $this->belongsTo(Setting::class, 'reason_rejection', 'id');
    }

    public function reasonCompletion() {
        return $this->belongsTo(Setting::class, 'interview_completed_reason', 'id');
    }

    public function rejectedBy() {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }
    
    public function location() {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function getFormattedInterviewCancellationDateAttribute()
    {
        return \Carbon\Carbon::parse($this->interview_cancellation_date)->format('m/d/Y h:i A');
    }

    public function interviewDates()
    {
        return $this->hasMany(CareerOpportunitiesInterviewDate::class, 'interview_id');
    }

    public function interviewMembers()
    {
        return $this->hasMany(CareerOpportunitiesInterviewMember::class, 'interview_id');
    }
}
