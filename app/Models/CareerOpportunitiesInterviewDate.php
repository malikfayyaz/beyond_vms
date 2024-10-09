<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesInterviewDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'schedule_date',
        'start_time',
        'end_time',
        'schedule_date_order',
    ];

    public function interview()
    {
        return $this->belongsTo(CareerOpportunitiesInterview::class, 'interview_id', 'id');
    }
}
