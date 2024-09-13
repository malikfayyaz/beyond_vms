<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Consultant;
use Carbon\Carbon;

class VendorController extends BaseController
{
    public function index()
    {
        return view('vendor.dashboard');
    }

    public function consultantDetail(Request $request)
    {
        // Validate that the candidate_id is present
        $request->validate([
            'candidate_id' => 'required|exists:consultants,user_id',
        ]);

        // Fetch the consultant's details using the candidate ID
        $consultant = Consultant::where('user_id', $request->input('candidate_id'))->first();

        if (!$consultant) {
            return response()->json(['message' => 'Consultant not found'], 404);
        }

        // Return a JSON response with the consultant's details
        return response()->json([
            'candidateFirstName' => $consultant->first_name,
            'candidateMiddleName' => $consultant->middle_name,
            'candidateLastName' => $consultant->last_name,
            'dobDate' => Carbon::parse($consultant->dob)->format('Y/m/d'),
            'lastFourNationalId' => substr($consultant->national_id, -4),
            // Add more fields as needed
        ]);
    }
    //
}
