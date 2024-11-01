<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class CareerOpportunitySubmission extends Model
{
    use SoftDeletes;
    protected $table = 'career_opportunities_submission';
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }


    public function getEstimateStartDateAttribute()
    {
        return $this->attributes['estimate_start_date'] ? Carbon::parse($this->attributes['estimate_start_date'])->format('m/d/Y') : '';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->attributes['created_at'] ? Carbon::parse($this->attributes['created_at'])->format('m/d/Y') : '';
    }
    public static function getSubmissionStatus($statusId)
    {
        switch ($statusId) {
            case "1":
                return 'Submitted';
            case "2":
                return 'MSP Review';
            case "3":
                return 'Shortlisted';
            case "4":
                return 'Client Review';
            case "5":
                return 'Interview Process';
            case "6":
                return 'Rejected';
            case "7":
                return 'Offer';
            case "8":
                return 'Approved';
            case "9":
                return 'Hired';
            case "10":
                return 'Review';
            case "11":
                return 'WorkOrder Release';
            case "12":
                return 'Withdraw';
            case "13":
                return 'No NDA';
            case "14":
                return 'NDA Pending';
            case "15":
                return 'Rehire Check';
            default:
                return 'Danger';
        }
    }
    public function rejectionReason(){
        return $this->belongsTo(Setting::class, 'reason_for_rejection', 'id');
    }

    public function rejectionUser(){
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    public function contracts() {
        return $this->hasMany(CareerOpportunitiesContract::class, 'submission_id', 'id');
    }

}
