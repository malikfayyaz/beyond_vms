<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesContract extends Model
{
    protected $table = 'career_opportunities_contract';
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }
    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }
    public function workOrder()
    {
        return$this->belongsTo(CareerOpportunitiesWorkorder::class,'workorder_id','id');
    }
    public function getDateRangeAttribute()
    {
        $start = $this->start_date ? Carbon::parse($this->start_date)->format('m/d/Y') : '';
        $end = $this->end_date ? Carbon::parse($this->end_date)->format('m/d/Y') : '';

        return $start && $end ? "$start - $end" : '';
    }
    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    public static function getContractStatus($statusId)
    {
        switch ($statusId) {
            case 0:
                return 'Draft';
            case 1:
                return 'Active';
            case 2:
                return 'Cancelled';
            case 3:
                return 'Approved 3';
            case 4:
                return 'Waiting For Supplier Approval 4';
            default:
                return 'danger N/A';
        }
    }
}
