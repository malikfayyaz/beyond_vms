<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkorderBackground extends Model
{
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'workorder_id', 'workorder_id');
    }
}
