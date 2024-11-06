<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractExtensionWorkflow extends Model
{
    //
    public function contract()
    {
        return $this->belongsTo(CareerOpportunitiesContract::class, 'contract_id');
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
