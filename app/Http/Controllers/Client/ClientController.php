<?php 
namespace App\Http\Controllers\Client;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;


class ClientController extends BaseController
{
    public function index()
    {
        return view('client.dashboard');
    }
}
