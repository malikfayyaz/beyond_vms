<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CareerOpportunitiesContract;

class ContractExtension extends Component
{
    public $contract;
    public $contractExtensionRequest;
    
    
    /**
     * Create a new component instance.
     */
    public function __construct(CareerOpportunitiesContract $contract)
    {
        $this->contract = $contract->load('careerOpportunity', 'ContractBudgetWorkflow','hiringManager','ContractExtensionRequest','ContractExtensionWorkflow');
        $this->contractExtensionRequest = $contract->ContractExtensionRequest->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-extension');
        }
}
