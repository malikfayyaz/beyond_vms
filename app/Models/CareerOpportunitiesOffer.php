<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CareerOpportunitiesOffer extends Model
{
    protected $table = 'career_opportunities_offer';
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

    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function getStartDateAttribute()
    {
        return $this->attributes['start_date'] ? Carbon::parse($this->attributes['start_date'])->format('m/d/Y') : '';
    }

    public function getEndDateAttribute()
    {
        return $this->attributes['end_date'] ? Carbon::parse($this->attributes['end_date'])->format('m/d/Y') : '';
    }
    public static function getStatus($statusId)
    {
        switch ($statusId) {
            case 0:
                return 'Draft';
            case 1:
                return 'Pending';
            case 2:
                return 'Rejected';
            case 3:
                return 'Approved';
            case 4:
                return 'Waiting For Supplier Approval';
            default:
                return 'danger';
        }
    }
}
