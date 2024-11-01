<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CareerOpportunitiesContract;

class ContractAdditionalBudget extends Component
{
    public $contract;
    public $rejectionreason;
    
    /**
     * Create a new component instance.
     */
    public function __construct(CareerOpportunitiesContract $contract, array $rejectionreason)
    {
        $this->contract = $contract->load('careerOpportunity', 'ContractExtensionRequest','ContractBudgetWorkflow','hiringManager');
        $this->rejectionreason = $rejectionreason;
        $this->contract->ContractAdditionalBudgetRequest = $contract->ContractAdditionalBudgetRequest()
        ->where('status', 'pending')
        ->latest()
        ->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-additional-budget', [
        'rejectionReasons' => $this->rejectionreason, // Pass the rejectionReasons to the view
    ]);
        }
}
