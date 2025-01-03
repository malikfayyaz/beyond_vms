<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractAdditionalBudget extends Model
{
    use HasFactory;

    protected $table = 'contract_additional_budget';

    protected $fillable = [
        'contract_id',
        'amount',
        'additional_budget_reason',
        'additional_budget_notes',
        'effective_date',
    ];
	public function contract()
    {
        return $this->belongsTo(CareerOpportunitiesContract::class, 'contract_id', 'id');
    }
    public function contractBudgetWorkflow()
    {
        return $this->hasMany(ContractBudgetWorkflow::class, 'request_id', 'id');
    }
    public function contractEditHistory()
    {
        return $this->belongsTo(ContractEditHistory::class, 'history_id', 'id');
    }
}
