<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
// use App\Models\Job;

class JobController extends BaseController
{

    public function create()
    {
        return view('admin.job.create');
    }


}
