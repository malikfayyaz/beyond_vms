<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericData extends Model
{
    use HasFactory;

    protected $table = 'generic_data'; // Specify the table name

    protected $fillable = ['name', 'status','value', 'symbol_id','country_id','type']; // Define the fillable attributes


    /**
     * Scope a query to only include records of a given type and status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTypeAndStatus($query, $type)
    {
        return $query->where('type', $type)
                     ->where('status', 'active')
                     ->get(['id', 'name','symbol_id','value']);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'symbol_id', 'id');
    }
    public function symbol()
    {
        return $this->belongsTo(Setting::class, 'symbol_id', 'id');
    }
}

