<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ContractExtensionRequest;

class ContractExtension extends Component
{
    public $extensionRequest;    
    
    /**
     * Create a new component instance.
     */
    public function __construct(ContractExtensionRequest $extensionRequest)
    {
        $this->extensionRequest = $extensionRequest;
        $this->extensionRequest->contract = $extensionRequest->contract;
        $this->extensionRequest->history = $extensionRequest->contractEditHistory;
        $this->extensionRequest->contractRates = $extensionRequest->contract->contractRates;
        $this->extensionRequest->workflow = $extensionRequest->contractExtensionWorkflow;    
 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contract-extension');
        }
}
