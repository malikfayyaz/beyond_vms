<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunityNote extends Model
{
    use HasFactory;
    protected $table = 'career_opportunity_notes';
    protected $fillable = [
        'created_by_id',
        'created_by_type',
        'job_id',
        'notes',
    ];
    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }
}
