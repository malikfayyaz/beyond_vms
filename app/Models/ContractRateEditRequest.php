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
