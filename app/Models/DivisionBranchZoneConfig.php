<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionBranchZoneConfig extends Model
{
    use HasFactory;

    protected $table = 'division_branch_zone_config'; // Corrected table name

    protected $fillable = [
        'bu_id',
        'division_id',
        'branch_id',
        'zone_id',
        'status',
    ];

    public function bu()
    {
        return $this->belongsTo(GenericData::class, 'bu_id', 'id');
    }

    // Define other relationships
    public function division()
    {
        return $this->belongsTo(GenericData::class, 'division_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(GenericData::class, 'branch_id', 'id');
    }

    public function zone()
    {
        return $this->belongsTo(GenericData::class, 'zone_id', 'id');
    }

    /**
     * Scope to filter active records.
     */
    public function scopeByStatus($query)
    {
        return $query->where('status', 'Active');
    }
}
