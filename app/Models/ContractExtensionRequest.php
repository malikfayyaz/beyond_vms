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
}
