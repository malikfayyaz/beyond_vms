<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    public function index()
    {
        return view('vendor.dashboard');
    }
    //
}
