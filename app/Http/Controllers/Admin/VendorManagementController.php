<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientCreated;
use App\Mail\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorCreatedMail;
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
            $vendors = Vendor::with('role', 'user')->get();

            return datatables()->of($vendors)
            ->addColumn('role', function($vendor) {
                return $vendor->role ? $vendor->role->name : 'N/A'; // Access the role name
            })
            ->addColumn('full_name', function ($vendor) {
                return $vendor->full_name; // Use the accessor method to get full name
            })
            ->addColumn('email', function ($vendor) {
                return $vendor->user->email; // Fetch email from the related User model
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
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'status' => 'required|string|in:Active,Under Review,Inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $request_email = $request->validate(['email' => 'required|email:users,email']);

        $user = User::where('email', $request_email)->first();

        if ($user) {
            // Check if a vendor record already exists for this user
            $vendorRecord = Vendor::where('user_id', $user->id)->first();
            if ($vendorRecord) {
                $errorMessage = 'A vendor record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.vendor-users.create') // Update redirect URL for vendors
                ]);
            } else {

                $validatedData['user_id'] = $user->id;
                $vendor = Vendor::create($validatedData);

                if ($request->hasFile('profile_image')) {
                    $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
                    $vendor->profile_image = $imagePath;
                    $vendor->save(); // Save after updating the profile image
                }

            }
        } else {

            $userData = [
                'name' => $validatedData['first_name'],
                'email' => $request_email['email'],
                'password' => Hash::make('password'),
                'is_admin' => 1,
            ];

            $user = User::create($userData);

            $validatedData['user_id'] = $user->id;

            $vendor = Vendor::create($validatedData);

            if ($request->hasFile('profile_image')) {
                $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
                $vendor->profile_image = $imagePath;
                $vendor->save(); // Save after updating the profile image
            }
        }

        $role = $validatedData['role'];

        // $this->updateRoles($vendor,$role);
        Mail::to($request_email)->send(new VendorCreated($vendor, $user));
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
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'status' => 'required|string|in:Active,Under Review,Inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $vendor = Vendor::findOrFail($id);

        $role = $validatedData['role'];

        $vendor->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'country' => $validatedData['country'],
            'status' => $validatedData['status'],
        ]);

        // Handle the profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($vendor->profile_image) {
                Storage::disk('public')->delete($vendor->profile_image);
            }

            $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
            if ($imagePath) {
                $vendor->profile_image = $imagePath;
                $vendor->save();
            }
        }


        // $this->updateRoles($vendor,$role);

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

    public function updateRoles($vendor,$role) {

        $userTypeId = 3;

        $user = User::findOrFail($vendor->user_id);

        // code for already exist role
        $existing_role = $user->with(['roles' => function ($query) use ($userTypeId) {
            $query->where('user_type_id', 2)
                  ->select('id', 'name', 'user_type_id'); // Select specific columns
        }])
        ->findOrFail($vendor->user_id);

        $alreadyroles = [];
        foreach($existing_role->roles as $rolesvalue) {
            $alreadyroles[] =$rolesvalue->name;
        }

        $newUpdatedRole =  $role;

        // Iterate through the already assigned roles
        foreach ($alreadyroles as $roles) {
            // Remove roles that are not in the new role set
            if (!in_array($roles, [$newUpdatedRole])) {
                // Fetch permissions associated with the role being removed
                $roleModel = \Spatie\Permission\Models\Role::findByName($roles);
                $permissionsToRemove = $roleModel->permissions->pluck('name')->toArray();
                // Remove the permissions from the user
                $user->revokePermissionTo($permissionsToRemove);

                // Remove the role
                $user->removeRole($roles);
            }
        }

        // Add the new role if it's not already assigned
        if (!in_array($newUpdatedRole, $alreadyroles)) {
            $user->assignRole($newUpdatedRole); // Assign the new role
        }

         // Fetch permissions associated with the new role
        $roleModel = \Spatie\Permission\Models\Role::findByName($newUpdatedRole);

        $permissions = $roleModel->permissions->pluck('name')->toArray(); // Get permission names

        // Sync user's permissions with the ones associated with the new role
        $user->syncPermissions($permissions);
    }
}
