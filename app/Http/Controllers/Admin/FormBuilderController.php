<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormBuilder;
use Yajra\DataTables\Facades\DataTables;


class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $forms = FormBuilder::latest()->get();
            
            return DataTables::of($forms)
            ->editColumn('status', function ($row) {
                return ucfirst($row->status) == 'Active' ? 'Active' : 'Inactive';
            })
            ->editColumn('type', function ($row) {
                // Assuming you have an array that maps types to human-readable labels
                $types = [
                    1 => 'Job',
                    2 => 'Submission',
                    3 => 'Offer'
                ];
            
                return $types[$row->type] ?? 'Unknown';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('admin.formbuilder.index', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('admin.formbuilder.edit', $row->id) . '" 
                            class="text-green-500 hover:text-green-700 bg-transparent hover:bg-transparent">
                                <i class="fas fa-edit"></i>
                        </a>';
            })->rawColumns(['action'])
            ->make(true);
           
        }
        
        return view('admin.formbuilder.index');
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
        // Validate request data
        $request->validate([
            'type' => 'required|integer',
            'data' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Save form data to the database
        $formBuilder = new FormBuilder();
        $formBuilder->type = $request->type;
        $formBuilder->data = $request->data;
        $formBuilder->status = $request->status;
        $formBuilder->created_by = auth()->id(); // Assume logged-in user ID
        $formBuilder->created_by_portal = 1; // Example: Set your portal ID
        $formBuilder->save();

        return response()->json([
            'success' => true,
            'message' => 'Form saved successfully!',
            'redirect_url' => route('admin.formbuilder.index') // Redirect back URL for AJAX
        ]);
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
        $formBuilder =  FormBuilder::findOrFail($id);
        return view('admin.formbuilder.create', compact('formBuilder'))
        ->with(['editMode' => true, 'editIndex' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'type' => 'required|integer',
            'data' => 'required|json',
            'status' => 'required|string',
        ]);
    
        $formBuilder = FormBuilder::findOrFail($id);
    
        // Update the form builder record
        $formBuilder->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Form update successfully!',
            'redirect_url' => route('admin.formbuilder.index') // Redirect back URL for AJAX
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function formBuilder()
    {
        return view('admin.formbuilder.create')->with(['editMode' => false, 'editIndex' => null]);
    }
}
