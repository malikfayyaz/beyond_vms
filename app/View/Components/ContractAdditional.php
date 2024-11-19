<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ContractAdditionalBudget;

class ContractAdditional extends Component
{
    public $budgetRequest;
    
    
    /**
     * Create a new component instance.
     */
    public function __construct(ContractAdditionalBudget $budgetRequest)
    {
        $this->budgetRequest = $budgetRequest;
        $this->budgetRequest->contract = $budgetRequest->contract;
        $this->budgetRequest->history = $budgetRequest->contractEditHistory;
        $this->budgetRequest->workflow = $budgetRequest->contractBudgetWorkflow;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-additional-budget');
        }
}
