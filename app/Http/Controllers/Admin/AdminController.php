<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function accountCode() {
     
        return view('admin.data-points.account_code');
    
    }
}
