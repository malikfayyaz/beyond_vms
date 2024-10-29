<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CareerOpportunity extends Model
{
    use SoftDeletes;
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public static function getStatus($statusId)
    {
        switch ($statusId) {
            case 0:
                return 'Draft';
            case 1:
                return 'Pending Approval';
            case 2:
                return 'Rejected';
            case 3:
                return 'Open - Pending Release';
            case 4:
                return 'Pending - PMO';
            case 13:
                return 'Sourcing';
            default:
                return 'danger'; 
        }
    }
    public function workFlow()
    {
        return $this->hasMany(JobWorkFlow::class,'job_id','id');
    }

    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager', 'id');
    }
    public function workerType()
    {
        return $this->belongsTo(Setting::class, 'job_type', 'id');
    }
    public function careerOpportunitiesBu()
    {
        return $this->hasMany(CareerOpportunitiesBu::class, 'career_opportunity_id');
    }
    public function division()
    {
        return $this->belongsTo(GenericData::class, 'division_id', 'id');
    }
    public function regionZone()
    {
        return $this->belongsTo(GenericData::class, 'region_zone_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo(GenericData::class, 'branch_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Setting::class, 'cat_id', 'id');
    }
    public function businessReason()
    {
        return $this->belongsTo(Setting::class, 'hire_reason_id', 'id');
    }
    public function jobType()
    {
        return $this->belongsTo(Setting::class, 'type_of_job', 'id');
    }
    public function glCode()
    {
        return $this->belongsTo(Setting::class, 'gl_code_id', 'id');
    }
    public function paymentType()
    {
        return $this->belongsTo(Setting::class, 'payment_type', 'id');
    }
    public function currency()
    {
        return $this->belongsTo(GenericData::class, 'currency_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getDateRangeAttribute()
     {
         $start = $this->start_date ? Carbon::parse($this->start_date)->format('m/d/Y') : '';
         $end = $this->end_date ? Carbon::parse($this->end_date)->format('m/d/Y') : '';

         return $start && $end ? "$start - $end" : '';
     }
    public function submissions() {
        return $this->hasMany(CareerOpportunitySubmission::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function rejectionReason(){
        return $this->belongsTo(Setting::class, 'reason_for_rejection', 'id');
    }

    public function rejectionUser(){
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    public function vendorJobRelease()
    {
        return $this->hasMany(VendorJobRelease::class, 'job_id', 'id');
    }

}
