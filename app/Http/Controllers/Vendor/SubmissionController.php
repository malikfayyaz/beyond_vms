<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
use App\Models\Markup;
use App\Models\Country;
use App\Models\Vendor;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $career_opportunity = CareerOpportunity::findOrFail($id);
        $markup = Markup::whereIn('category_id', [$career_opportunity->cat_id])
                ->orWhereIn('location_id', [$career_opportunity->location_id])
                ->orWhereIn('vendor_id', [\Auth::id()])
                ->first();
                $markupValue = $markup ? $markup->markup_value : 0;
        $countries = Country::all();       
        $vendor = Vendor::where('user_id', \Auth::id())->first();
        return view('vendor.submission.create',[
            'career_opportunity'=>$career_opportunity,
            'markup'=>$markupValue,
            'countries'=> $countries,'vendor'=> $vendor ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
