<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRateEditRequest extends Model
{
    protected $guarded = [
        // List attributes you want to guard from mass assignment
        // e.g., 'id', 'created_at', 'updated_at'
    ];

    public static function getContractRateUpdateStatus() {
        return array(
            '0' => 'Pending',
            '1' => 'Approved',
            '2' => 'Rejected',
            '3' => 'Vendor Approval',
        );
    }
    

    public function contract()
    {
        return $this->belongsTo(CareerOpportunitiesContract::class, 'contract_id', 'id');
    }

    public function contractEditHistory()
    {
        return $this->belongsTo(ContractEditHistory::class, 'history_id', 'id');
    }

    public function contractRatesEditWorkflow()
    {
        return $this->hasMany(ContractRatesEditWorkflow::class, 'request_id', 'id');
    }
}
