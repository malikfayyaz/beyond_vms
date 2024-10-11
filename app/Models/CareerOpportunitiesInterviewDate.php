<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function scopePrimaryDate($query)
    {
        $primaryDate = $query->where('schedule_date_order', 1)->first();
        
        if ($primaryDate) {
            $primaryDate->schedule_date = Carbon::parse($primaryDate->schedule_date)->format('m/d/Y');
        }

        return $primaryDate;
    }

    public function getFormattedStartTimeAttribute()
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('h:i A');
    }

    public function getFormattedEndTimeAttribute()
    {
        return \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('h:i A');
    }
}
