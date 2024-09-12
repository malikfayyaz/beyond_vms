<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller as BaseController;

class VendorController extends BaseController
{
    public function index()
    {
        return view('vendor.dashboard');
    }
    //
}
