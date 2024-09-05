<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionBranchZoneConfig extends Model
{
    use HasFactory;

    protected $table = 'division_branch_zone_config'; // Define the table name

    protected $fillable = [
        'division',
        'branch',
        'zone',
        'bu',
        'status',
    ];

    // Define relationships if necessary
    public function division()
    {
       return $this->belongsTo(GenericData::class, 'division');
    }

    public function branch()
    {
        return $this->belongsTo(GenericData::class, 'branch');
    }

    public function zone()
    {
        return $this->belongsTo(GenericData::class, 'region-zone');
    }

    public function bu()
    {
        return $this->belongsTo(GenericData::class, 'busines-unit');
    }
}
