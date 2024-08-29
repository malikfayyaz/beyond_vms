<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    // use HasFactory;
    protected $guarded = [ 
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
