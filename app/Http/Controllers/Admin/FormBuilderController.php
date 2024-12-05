<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormBuilder;

class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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

        return response()->json(['success' => true, 'message' => 'Form saved successfully!']);
    
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

    public function formBuilder()
    {
        return view('admin.formbuilder.create');
    }
}
