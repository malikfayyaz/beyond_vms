<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesBu extends Model
{
    use HasFactory;

    protected $table = 'career_opportunities_bu';

    protected $fillable = [
        'career_opportunity_id',
        'bu_unit',
        'percentage',
    ];

    // Relationship with CareerOpportunity model
    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class);
    }

    public function buName()
    {
        return $this->belongsTo(GenericData::class, 'bu_unit');
    }
}
