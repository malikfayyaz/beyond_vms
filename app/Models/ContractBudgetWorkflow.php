<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractBudgetWorkflow extends Model
{
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];
    public function contract() {
        return $this->belongsTo(Setting::class, 'termination_reason', 'id');
    }
    public function hiringManager()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function getApproverTypeAttribute()
    {
        switch ($this->approve_reject_by) {
            case 1:
                return 'MSP';
            case 2:
                return 'Client';
            default:
                return 'N/A';
        }
}
}
