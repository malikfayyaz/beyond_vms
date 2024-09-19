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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $user = Auth::user();
        $countries = Country::all();
        // Validation
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'status' => 'required|string|in:active,inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        $email_found = User::where('email', $request->email)->first();

        if ($email_found) {

            $adminRecord = Client::where('user_id', $email_found->id)->first();

            if ($adminRecord) {

                $errorMessage = 'An admin record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.client-users.create') // Send back the URL to redirect to
                ]);

            } else {

                $client = new Client();
                $client->user_id = $email_found->id;
                $client->first_name = $request->first_name;
                $client->last_name = $request->last_name;
                $client->email = $request->email;
                $client->phone = $request->phone;
                $client->member_access = $request->role; // Assuming you have a foreign key to roles in the Admin table
                $client->admin_status = 1;
                $client->country = $request->country; // Assuming you have a foreign key to countries
                $client->status = $request->status;

                // Handle the profile image upload if provided
                $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                if ($imagePath) {
                    $client->profile_image = $imagePath;
                }

                $client->save();
            }
        } else {

            $user = new User;
            $user->name = $request->first_name;
            $user->email = $request->email;
            $user->password = Hash::make('password');
            $user->is_admin = 1;

            $user->save();

            $client = new $client;
            $client->user_id = $user->id;
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->member_access = $request->role; // Assuming you have a foreign key to roles in the Admin table
            $client->admin_status = 1;
            $client->country = $request->country; // Assuming you have a foreign key to countries
            $client->status = $request->status;

            //  Handle the profile image upload if provided

            $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
            if ($imagePath) {
                $client->profile_image = $imagePath;
            }

            $client->save();

            $roles_permission = DB::table('role_has_permissions')->where('role_id', $request->role)->get();
            foreach($roles_permission as $permission){
                $insert = DB::table('model_has_permissions')->insert([
                    'model_id' => $user->id, // Associating with the user
                    'permission_id' => $permission->permission_id, // Adjust field as per your table structure
                    'model_type' => 'App\Models\User', // Assuming you're using the User model
                ]);
            }

            $roles = DB::table('roles')->where('id', $request->role)->get(); // Assuming you're getting role from request
            foreach ($roles as $role) {
                $insert = DB::table('model_has_roles')->insert([
                    'role_id' => $role->id, // Role ID from the roles table
                    'model_id' => $user->id, // Associating the role with the user
                    'model_type' => 'App\Models\User', // The model being associated, usually 'User'
                ]);
            }
        }

        $successMessage = 'Client created successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("admin.client-users.index")  // Redirect URL for AJAX
        ]);
    }









/*    public function store(Request $request)
    {
        $userId = auth()->id();

        // Common validation rules
        $commonRules = [
            'first_name' => 'required',
            'middle_name' => 'nullable',  // Optional middle name
            'last_name' => 'required',
            'email' => 'required|email|unique:clients,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'required',
            'description' => 'required',
            'profile_status' => 'required',
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

        // Create a new Client record
        $client = new Client();
        $client->fill($validatedData);
        $client->save(); // Save the client to the database

        // Return success response
        return response()->json(['success' => true, 'redirect_url' => route('admin.client-users.index')]);
    }*/

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
