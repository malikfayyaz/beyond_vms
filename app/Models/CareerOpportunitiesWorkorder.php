<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CareerOpportunitiesWorkorder extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'career_opportunities_workorder';
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    protected static $logAttributes = ['*']; // Logs all attributes by default
    protected static $logOnlyDirty = true;     // Logchanged attributes
    protected static $logName = 'workorder';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            // ->logOnlyDirty()
            ->useLogName('workorder')
            ->dontLogIfAttributesChangedOnly(['updated_at']);
    }

    
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }
    public function contract()
    {
        return $this->hasOne(CareerOpportunitiesContract::class, 'workorder_id', 'id');
    }
    public function approvalManager()
    {
        return $this->belongsTo(Client::class, 'approval_manager', 'id');
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
        return $this->belongsTo(CareerOpportunitiesOffer::class, 'offer_id' , 'id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
    }
    public function workorderbackground()
    {
        return $this->belongsTo(WorkorderBackground::class, 'id', 'workorder_id');
    }
    public function jobType()
    {
        return $this->belongsTo(Setting::class, 'job_type', 'id');
    }
    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? Carbon::parse($this->start_date)->format('m/d/Y') : '';
        $end = $this->end_date ? Carbon::parse($this->end_date)->format('m/d/Y') : '';

        return $start && $end ? "$start - $end" : '';
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    public static function getWorkorderStatus($statusId)
    {
        switch ($statusId) {
            case '0':
                return 'Pending';
            case '1':
                return 'Approved';
            case '2':
                return 'Rejected';
            case '3':
                return 'Closed';
            case '4':
                return 'Expired';
            case '5':
                return 'Rehire';
            case '6':
                return 'Withdrawn';
            case '7':
                return 'Pending Approval';
            case '14':
                return 'Cancelled';
            default:
                return 'Unknown Status'; // Fallback for unrecognized status IDs
        }
    }
}
