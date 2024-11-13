<?php

namespace App\View\Components;

use Illuminate\View\Component;

class JobHistory extends Component
{
    public $log;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $log
     * @return void
     */
    public function __construct($log)
    {
        $this->log = $log;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.job-history');
    }
}
