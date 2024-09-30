<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CareerOpportunitiesWorkorder extends Model
{
    protected $table = 'career_opportunities_workorder';
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
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function consultant()
    {
        return $this->belongsTo(Consultant::class, 'candidate_id', 'id');
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
}
