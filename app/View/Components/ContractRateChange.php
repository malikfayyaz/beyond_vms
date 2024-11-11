<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CareerOpportunitiesContract;

class ContractRateChange extends Component
{
    public $contract;    
    
    /**
     * Create a new component instance.
     */
    public function __construct(CareerOpportunitiesContract $contract)
    {
        dd('here');
    $this->contract = $contract;
    $this->contract->latestRateEditRequest = $contract->latestRateEditRequest();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-rate-change');
        }
}
