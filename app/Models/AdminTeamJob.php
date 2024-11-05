<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTeamJob extends Model
{
    use HasFactory;

    public function careerOpportunity()
    {
        return $this->belongsTo(CareerOpportunity::class, 'career_opportunity_id' , 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
