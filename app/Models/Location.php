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

     /**
     * Scope a query to only include records of a given type and status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query)
    {
        return $query->where('status', 'active')
                     ->get();
    }

     // Define the accessor for location details
     public function getLocationDetailsAttribute()
     {
         $countryName = $this->country ? $this->country->name : 'N/A';
 
         return trim($this->name . ', ' . $this->address1 . ', ' . $countryName);
     }
}
