<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'hiring_manager_id', 'id');
    }

    public function venDor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}
