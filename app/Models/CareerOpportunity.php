<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerOpportunity extends Model
{
    use SoftDeletes;
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function careerOpportunitiesBu()
    {
        return $this->hasMany(CareerOpportunitiesBu::class, 'career_opportunity_id');
    }
}
