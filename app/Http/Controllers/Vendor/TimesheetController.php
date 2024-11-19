<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    //
    public function selectCandidate()
    {
       
       return view('vendor.timesheet.select_candidate');
    }
}
