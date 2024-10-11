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
use App\Models\VendorTeammember;
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
            $vendors = Vendor::with('user')->get();

            return datatables()->of($vendors)
            ->addColumn('full_name', function ($vendor) {
                return $vendor->full_name; // Use the accessor method to get full name
            })
            ->addColumn('profile_status', function ($vendor) {
                return $vendor->profile_status == 1 ? 'Active' : 'Inactive'; // Check status and return text
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
        $roles = Role::where('user_type_id', 3)->get();
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
            // 'organization' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'profile_status' => 'required|boolean',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $validatedData['profile_approve'] = 'Yes';

        $request_email = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request_email)->first();

                $getParentvendor = Vendor::where('member_type', 0)
                ->where('organization', $request->organization)
                ->first();

            if (!empty($getParentvendor)) {
                $organization = '';
                $memberType = 3;
                $parentVendorID = $getParentvendor->id;
            } else {
                $organization = $request->organization;
                $parentVendorID = 0;
                $memberType = 0;
            }
            $validatedData['member_type'] = $memberType;
            $validatedData['parent_id'] =  $parentVendorID;
            $validatedData['organization'] = $organization;

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
                'is_vendor' => 1,
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
        $vendorteamMember = [
            'vendor_id' => $parentVendorID,
            'teammember_id' => $vendor->id,
        ];
        if (!empty($getParentvendor)) {
            VendorTeammember::create($vendorteamMember);
        }




        $role = $validatedData['role'];

        $this->updateRoles($vendor,$role);

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
        $roles = Role::where('user_type_id', 3)->get();

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
            'profile_status' => 'required|boolean',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $vendor = Vendor::findOrFail($id);

        $role = $validatedData['role'];

        $vendor->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'country' => $validatedData['country'],
            'profile_status' => $validatedData['profile_status'],
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


        $this->updateRoles($vendor,$role);

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

        // Get the existing roles for the user (with user_type_id = 1)
        $alreadyRoles = $user->roles()->where('user_type_id', $userTypeId)->pluck('name')->toArray();

        // Check if $role is an ID or a name
        if (is_numeric($role)) {
            // If it's a number (ID), find the role by ID
            $roleModel = \Spatie\Permission\Models\Role::find($role);

            // Check if the role exists
            if (!$roleModel) {
                return response()->json(['error' => 'Role ID does not exist.'], 404);
            }

            $newUpdatedRole = $roleModel->name; // Get the role name from the model
        } else {
            $newUpdatedRole = $role; // If it's a name, use it directly
        }

        // Remove roles that are not in the new role set
        foreach ($alreadyRoles as $existingRole) {
            if ($existingRole !== $newUpdatedRole) {
                // Remove the role and its permissions
                $user->removeRole($existingRole);
            }
        }

        // Assign the new role if it's not already assigned
        if (!in_array($newUpdatedRole, $alreadyRoles)) {
            $user->assignRole($newUpdatedRole);
        }

        // Fetch permissions associated with the new role
        $roleModel = \Spatie\Permission\Models\Role::findByName($newUpdatedRole);
        if ($roleModel) {
            $permissions = $roleModel->permissions->pluck('name')->toArray();
            // Sync user's permissions with the ones associated with the new role
            $user->syncPermissions($permissions);
        }
    }
}
