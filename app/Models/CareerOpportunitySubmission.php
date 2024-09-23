<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitySubmission extends Model
{
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
    
    public function workerType()
    {
        return $this->belongsTo(Setting::class, 'worker_type_id', 'id');
    }
   
}
