<?php

namespace App\View\Components;

use Illuminate\View\Component;

class JobHistory extends Component
{
    public $job;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function render()
    {
        return view('components.job-history');
    }
}
