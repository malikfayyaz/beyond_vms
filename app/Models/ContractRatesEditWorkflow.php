<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractRatesEditWorkflow extends Model
{
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
