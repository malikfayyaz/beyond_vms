<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CareerOpportunitiesContract;

class ContractAdditionalBudget extends Component
{
    public $contract;
    /**
     * Create a new component instance.
     */
    public function __construct(CareerOpportunitiesContract $contract)
    {
        $this->contract = $contract->load('careerOpportunity', 'ContractExtensionRequest');
        //
         // Load only the latest pending ContractAdditionalBudgetRequest as a single object
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
        return view('components.contract-additional-budget');
    }
}
