<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Country;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager load the role relationship (if vendors have roles)
            $vendors = Vendor::with('role')->select(['id', 'first_name', 'last_name', 'email', 'status', 'member_access'])->get();
            
            return datatables()->of($vendors)
                ->addColumn('role', function($vendor) {
                    return $vendor->role ? $vendor->role->name : 'N/A'; // Access the role name
                })
                ->addColumn('action', function($vendor) {
                    return '<a href="'. route('admin.vendor-users.show', $vendor->id) .'" class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                        <i class="fas fa-eye"></i>
                    </a>

                    <a href="' . route('admin.vendor-users.edit', $vendor->id) . '"
                        class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                        >
                        <i class="fas fa-edit"></i>
                        </a>
                    
                            <form action="'. route('admin.vendor-users.destroy', $vendor->id) .'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.vendor_users.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming user_type_id for vendors is different from admins, e.g., 2 for vendors
        $roles = Role::where('user_type_id', 2)->get();
        $user = Auth::user();
        $countries = Country::all();
        
        return view('admin.users.vendor_users.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();   
        $countries = Country::all();

        // Validation
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email', // Assuming the vendors table is 'vendors'
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'status' => 'required|string|in:active,inactive',
        ]);

        $first_name = $validatedData['first_name'];
        $last_name = $validatedData['last_name'];
        $email = $validatedData['email'];
        $phone = $validatedData['phone'];
        $role = $validatedData['role'];
        $country = $validatedData['country'];
        $status = $validatedData['status'];

        // Check if the email is already used by another user
        $email_found = User::where('email', $email)->first();
    
        if ($email_found) {
            // Check if a vendor record already exists for this user
            $vendorRecord = Vendor::where('user_id', $email_found->id)->first();
            
            if ($vendorRecord) {
                $errorMessage = 'A vendor record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.vendor-users.create') // Update redirect URL for vendors
                ]);
            } else {
                // Create a new Vendor record
                $vendor = new Vendor;
                $vendor->user_id = $email_found->id;
                $vendor->first_name = $first_name;
                $vendor->last_name = $last_name;
                $vendor->email = $email;
                $vendor->phone = $phone;
                $vendor->member_access = $role;
                $vendor->country = $country; // Assuming country_id is a foreign key
                $vendor->status = $status;

                // Handle profile image upload if provided
                $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
                if ($imagePath) {
                    $vendor->profile_image = $imagePath;
                }
                // dd($vendor);
                $vendor->save();
            }
        } else {
            // Create a new User record
            $user = new User;      
            $user->name = $first_name;
            $user->email = $email;
            $user->password = Hash::make('password'); // Assign default password
            $user->is_vendor = 1; // Assuming you have a field to identify vendors

            $user->save();

            // Create a new Vendor record
            $vendor = new Vendor;
            $vendor->user_id = $user->id;
            $vendor->first_name = $first_name;
            $vendor->last_name = $last_name;
            $vendor->email = $email;
            $vendor->phone = $phone;
            $vendor->member_access = $role;
            $vendor->country = $country;
            $vendor->status = $status;

            // Handle profile image upload if provided
            $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
            if ($imagePath) {
                $vendor->profile_image = $imagePath;
            }

            $vendor->save();

            // Assign role permissions to the new vendor user
            $roles_permission = DB::table('role_has_permissions')->where('role_id', $role)->get();
            foreach ($roles_permission as $permission) {
                DB::table('model_has_permissions')->insert([
                    'model_id' => $user->id,
                    'permission_id' => $permission->permission_id,
                    'model_type' => 'App\Models\User', // Ensure correct model type
                ]);
            }

            // Assign role to the vendor user
            $roles = DB::table('roles')->where('id', $role)->get();
            foreach ($roles as $roleData) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleData->id,
                    'model_id' => $user->id,
                    'model_type' => 'App\Models\User',
                ]);
            }
        }

        $successMessage = 'Vendor created successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route("admin.vendor-users.index")  // Redirect URL for vendors listing
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vendor = Vendor::findOrFail($id);

        return view('admin.users.vendor_users.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the vendor by ID
        $vendor = Vendor::findOrFail($id);
        
        // Fetch roles for vendors (assuming user_type_id 2 is for vendors)
        $roles = Role::where('user_type_id', 2)->get();
        
        // Fetch all countries
        $countries = Country::all();

        // Return the vendor edit view, passing relevant data
        return view('admin.users.vendor_users.create', [
            'vendor' => $vendor,
            'roles' => $roles,
            'countries' => $countries,
            'editMode' => true,
            'editIndex' => $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'status' => 'required|string|in:active,inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $first_name = $validatedData['first_name'];
        $last_name = $validatedData['last_name'];
        $email = $validatedData['email'];
        $phone = $validatedData['phone'];
        $role = $validatedData['role'];
        $country = $validatedData['country'];
        $status = $validatedData['status'];

        // Find the vendor
        $vendor = Vendor::findOrFail($id);
        $vendor->first_name = $first_name;
        $vendor->last_name = $last_name;
        $vendor->phone = $phone;
        $vendor->member_access = $role;
        $vendor->country = $country;
        $vendor->status = $status;

        // Handle the profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($vendor->profile_image) {
                Storage::disk('public')->delete($vendor->profile_image);
            }

            $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
            if ($imagePath) {
                $vendor->profile_image = $imagePath;
            }
        }

        $vendor->save();

        // Remove old permissions and roles
        DB::table('model_has_permissions')->where('model_id', $vendor->user_id)->where('model_type', 'App\Models\User')->delete();
        DB::table('model_has_roles')->where('model_id', $vendor->user_id)->where('model_type', 'App\Models\User')->delete();

        // Assign new permissions
        $roles_permission = DB::table('role_has_permissions')->where('role_id', $role)->get();
        foreach ($roles_permission as $permission) {
            DB::table('model_has_permissions')->insert([
                'model_id' => $vendor->user_id, // Associating with the user
                'permission_id' => $permission->permission_id, // Adjust field as per your table structure
                'model_type' => 'App\Models\User', // Assuming you're using the User model
            ]);
        }

        // Assign new roles
        $roles = DB::table('roles')->where('id', $role)->get();
        foreach ($roles as $roleData) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleData->id, // Role ID from the roles table
                'model_id' => $vendor->user_id, // Associating the role with the user
                'model_type' => 'App\Models\User', // The model being associated, usually 'User'
            ]);
        }

        // Return success response
        $successMessage = 'Vendor updated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route("admin.vendor-users.index")  // Redirect URL for AJAX
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the vendor by ID
        $vendor = Vendor::findOrFail($id);

        // Check if the vendor has a profile image and delete the file if it exists
        if ($vendor->profile_image && \Storage::disk('public')->exists($vendor->profile_image)) {
            \Storage::disk('public')->delete($vendor->profile_image);
        }

        // Delete the vendor record
        $vendor->delete();

        // Redirect back with success message (for non-AJAX requests)
        return redirect()->route('admin.vendor-users.index')->with('success', 'Vendor deleted successfully');
    }
}
