<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CareerOpportunitiesContract;

class ContractExtension extends Component
{
    public $contract;    
    
    /**
     * Create a new component instance.
     */
    public function __construct(CareerOpportunitiesContract $contract)
    {
        $this->contract = $contract;
        $this->contract->contractExtensionRequest = $contract->latestApprovedExtensionRequest();
 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-extension');
        }
}
