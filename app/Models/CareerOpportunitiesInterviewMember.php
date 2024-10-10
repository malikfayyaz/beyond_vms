<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunitiesInterviewMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'member_id',
    ];

    public function interview()
    {
        return $this->belongsTo(CareerOpportunitiesInterview::class, 'interview_id', 'id');
    }

    public function member()
    {
        return $this->belongsTo(Client::class, 'member_id', 'id');
    }
}
