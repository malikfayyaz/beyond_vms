<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CareerOpportunity;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class ClientManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
              $data = Client::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return $row->full_name; // Use the accessor method to get full name
                })
                ->addColumn('action', function($row){

                    $btn = ' <a href="' . route('admin.client-users.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.client-users.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('admin.client-users.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
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
        return view('admin.users.client-users.index'); // Assumes you have a corresponding Blade view
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('user_type_id', 2)->get();
        $client = Auth::user();
        $countries = Country::all();
         //dd($client);

        return view('admin.users.client-users.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();

        // Common validation rules
        $commonRules = [
            'first_name' => 'required',
            'middle_name' => 'nullable',  // Optional middle name
            'last_name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role' => 'required',
            'country' => 'required',
            'description' => 'required',
            'status' => 'required',
        ];

        // Role-specific validation rules
        $roleSpecificRules = [
            'middle_name' => 'required',
            'business_name' => 'required',
            'organization' => 'required',
        ];

        // Merge common and role-specific rules
        $rules = array_merge($commonRules, $roleSpecificRules);

        // Validate request
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Check if the email already exists in the 'clients' table
        $existingClient = Client::where('email', $request->email)->first();

        if ($existingClient) {
            $errorMessage = 'A client with this email already exists.';
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'redirect_url' => route('admin.client-users.index') // Send back the URL to redirect to
            ]);
        }

        // Get validated data
        $validatedData = $validator->validated();

        // Handle profile image upload if present
        $filename = handleFileUpload($request, 'profile_image', 'profile_images');
        if ($filename) {
            $validatedData['profile_image'] = $filename;
        }

        // Add additional data
        $validatedData['profile_approved_date'] = Carbon::now();
        $validatedData['user_id'] = $userId; // Associate the client with the authenticated user
        $validatedData['manager_id'] = '1'; // Set default manager
        $validatedData['profile_status'] = '1'; // Set default profile status

        // Create a new Client record
        $client = new Client();
        $client->fill($validatedData);
        $client->save(); // Save the client to the database

        // Return success response
        return response()->json(['success' => true, 'redirect_url' => route('admin.client-users.index')]);
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
