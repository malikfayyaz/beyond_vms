<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
use App\Models\JobTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clientid = Auth::id();
            $data = CareerOpportunity::with('hiringManager','workerType')
                ->where('user_id', $clientid)
                ->select('career_opportunities.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function($row) {
                    return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('duration', function($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('worker_type', function($row) {
                    return $row->workerType ? $row->workerType->title : 'N/A';
                })
                /*            $data = CareerOpportunity::query();
                            return Datatables::of($data)
                                    ->addIndexColumn()*/
                ->addColumn('action', function($row){

                    $btn = ' <a href="' . route('client.career-opportunities.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('client.career-opportunities.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('client.career-opportunities.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn .$deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('client.career-opportunities.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $careerOpportunity ="";
        $businessUnitsData = "";
        return view('client.career-opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
        ] );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validatedData = $this->validateJobOpportunity($request);

            $businessUnits = $request->input('businessUnits');
            // dd($businessUnits);

            $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
            // Handle file upload

            $filename = handleFileUpload($request, 'attachment', 'career_opportunities');
            // Mapping form fields to database column names
            $mappedData = $this->mapJobData($validatedData, $jobTemplate, $request, $filename);
            $job = CareerOpportunity::create( $mappedData );

            $this->syncBusinessUnits($request->input('businessUnits'), $job->id);

            calculateJobEstimates($job);
            session()->flash('success', 'Job saved successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Job saved successfully!',
                'redirect_url' => route('admin.career-opportunities.index') // Redirect back URL for AJAX
            ]);
        }catch (ValidationException $e) {
            // Handle the validation error and return a JSON response
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Returns all the validation errors
                'message' => 'Validation failed!',
            ], 422);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = CareerOpportunity::with('hiringManager')->findOrFail($id);

        // Optionally, you can dump the data for debugging purposes
        // dd($job); // Uncomment to check the data structure

        // Return the view and pass the job data to it
        return view('client.career-opportunities.view', compact('job'));
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
