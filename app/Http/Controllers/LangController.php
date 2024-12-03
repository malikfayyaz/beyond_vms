<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('lang');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);
        App::setLocale(session('locale'));
//        dd(App::getLocale());
        return redirect()->back();
    }
}
