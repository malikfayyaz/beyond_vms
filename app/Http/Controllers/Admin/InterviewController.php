<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;


class InterviewController extends Controller
{
    public function create($id)
    {
        $submission =  CareerOpportunitySubmission::findOrFail($id);

        return view('admin.interview.create', compact('submission'));
    }
}
