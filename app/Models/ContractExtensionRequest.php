<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractExtensionRequest extends Model
{
    use HasFactory;
    public function contract()
    {
        return $this->belongsTo(CareerOpportunitiesContract::class, 'contract_id');
    }
    public function contractExtensionWorkflow()
    {
        return $this->hasMany(ContractExtensionWorkflow::class, 'request_id', 'id');
    }
    public function contractEditHistory()
    {
        return $this->belongsTo(ContractEditHistory::class, 'history_id', 'id');
    }
    public function contractRates()
    {
        return $this->hasOne(ContractRate::class, 'contract_id', 'id');
    }
}
