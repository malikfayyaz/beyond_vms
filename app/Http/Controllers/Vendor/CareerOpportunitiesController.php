<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
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
            $vendorid = Auth::id();
            $data = CareerOpportunity::with('hiringManager','workerType')
                ->where('user_id', $vendorid)
                ->select('career_opportunities.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function($row) {
                    return $row->hiringManager ? $row->hiringManager->first_name : 'N/A';
                })
                ->addColumn('worker_type', function($row) {
                    return $row->workerType ? $row->workerType->title : 'N/A';
                })
                /*            $data = CareerOpportunity::query();
                            return Datatables::of($data)
                                    ->addIndexColumn()*/
                ->addColumn('action', function($row){

                    $btn = ' <a href="' . route('vendor.career-opportunities.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('vendor.career-opportunities.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
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
        // Logic to get and display catalog items
        return view('vendor.career_opportunities.index'); // Assumes you have a corresponding Blade view
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $job = CareerOpportunity::with('hiringManager')->findOrFail($id);
       // dd($job);
        // Optionally, you can dump the data for debugging purposes
        // dd($job); // Uncomment to check the data structure

        // Return the view and pass the job data to it
        return view('vendor.career_opportunities.view', compact('job'));
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
