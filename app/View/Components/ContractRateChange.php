<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ContractRateEditRequest;

class ContractRateChange extends Component
{
    public $rateEditRequest;    
    
    /**
     * Create a new component instance.
     */
    public function __construct(ContractRateEditRequest $rateEditRequest)
    {
        
    $this->rateEditRequest = $rateEditRequest;
    
    $this->rateEditRequest->contract = $rateEditRequest->contract;
    $this->rateEditRequest->history = $rateEditRequest->contractEditHistory;
    $this->rateEditRequest->workflow = $rateEditRequest->contractRatesEditWorkflow;
    
    // dd($this->rateEditRequest);
   
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-rate-change');
        }
}
