<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitySubmission;
use Illuminate\Http\Request;

class CareerOpportunitiesOfferController extends BaseController
{
    // Display a listing of career opportunities
    public function index()
    {
        // $offers = CareerOpportunitiesOffer::all();
        $offers = "offer";
        return view('admin.offer.index', compact('offers'));
    }

    // Show the form for creating a new career opportunity offer
    public function create($id)
    {
       $submission =  CareerOpportunitySubmission::findOrFail($id);
       return view('admin.offer.create',[
        'submission'=>$submission
         ]);
    }

    // Store a newly created career opportunity offer in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'nullable|string',
            'location' => 'required|string',
            'salary' => 'nullable|numeric',
            'employment_type' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ]);

        CareerOpportunitiesOffer::create($validatedData);

        return redirect()->route('admin.offer.index')->with('success', 'Career opportunity offer created successfully.');
    }

    // Show a specific career opportunity offer
    public function show($id)
    {
        // $offer = CareerOpportunitiesOffer::findOrFail($id);
        $offer = "offer";
        return view('admin.offer.show', compact('offer'));
    }

    // Show the form for editing an existing career opportunity offer
    public function edit($id)
    {
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        return view('admin.offer.edit', compact('offer'));
    }

    // Update the specified career opportunity offer in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'nullable|string',
            'location' => 'required|string',
            'salary' => 'nullable|numeric',
            'employment_type' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ]);

        $offer = CareerOpportunitiesOffer::findOrFail($id);
        $offer->update($validatedData);

        return redirect()->route('admin.offer.index')->with('success', 'Career opportunity offer updated successfully.');
    }

    // Remove the specified career opportunity offer from the database
    public function destroy($id)
    {
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        $offer->delete();

        return redirect()->route('admin.offer.index')->with('success', 'Career opportunity offer deleted successfully.');
    }
}
