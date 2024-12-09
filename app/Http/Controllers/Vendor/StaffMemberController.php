<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Mail\VendorCreated;
use App\Models\CareerOpportunitySubmission;
use App\Models\Country;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorTeammember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StaffMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vendors = Vendor::with('user')->get();
            return DataTables::of($vendors)
                ->addColumn('full_name', function ($vendor) {
                    return $vendor->full_name; // Use the accessor method to get full name
                })
                ->addColumn('email', function ($vendor) {
                    return $vendor->user->email; // Fetch email from the related User model
                })
                ->addColumn('profile_status', function ($vendor) {
                    return $vendor->profile_status == 1 ? 'Active' : 'Inactive'; // Check status and return text
                })
                ->addColumn('action', function($vendor) {
                    return '<a href="' . route('vendor.staffmember.edit', $vendor->id) . '"
                        class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                        >
                        <i class="fas fa-edit"></i>
                        </a>
                            <form action="'. route('vendor.staffmember.destroy', $vendor->id) .'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['id','action'])
                ->make(true);
        }
        return view('vendor.staff_member.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('user_type_id', 3)->get();
        $user = Auth::user();
        $countries = Country::all();

        return view('vendor.staff_member.create', compact('roles', 'countries'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $countries = Country::all();
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

        $validatedData['profile_approve'] = 'Yes';

        $request_email = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request_email)->first();
        $getParentvendor = Vendor::where('member_type', 0)
            ->first();
        if (!empty($getParentvendor)) {
            $organization = $request->organization;
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
        $vendorId = Vendor::getVendorIdByUserId($userId);
        if ($user) {
            $vendorRecord = Vendor::where('user_id', $user->id)->first();
            if ($vendorRecord) {
                $successMessage = 'A vendor record already exists for this email.!';
                session()->flash('success', $successMessage);
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect_url' => route("vendor.staffmember.index")  // Redirect URL for vendors listing
                ]);
            }
            else{
                $validatedData['user_id'] = $user->id;
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
        }
        $vendor = Vendor::create($validatedData);
        if ($request->hasFile('profile_image')) {
            $imagePath = handleFileUpload($request, 'profile_image', 'vendor_profile');
            $vendor->profile_image = $imagePath;
        }
        $vendorteamMember = [
            'vendor_id' => $parentVendorID,
            'teammember_id' => $vendor->id,
        ];
        if ($parentVendorID) {
           $team = VendorTeammember::create($vendorteamMember);
        }
        $role = $validatedData['role'];
        $this->updateRoles($vendor,$role);
        $successMessage = 'Vendor created successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route("vendor.staffmember.index")  // Redirect URL for vendors listing
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
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
       $roles = Role::where('user_type_id', 3)->get();
       $countries = Country::all();
         return view('vendor.staff_member.create', [
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
        if ($request->hasFile('profile_image')) {
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
        $successMessage = 'Vendor updated successfully!';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route("vendor.staffmember.index")
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        if ($vendor->profile_image && \Storage::disk('public')->exists($vendor->profile_image)) {
            \Storage::disk('public')->delete($vendor->profile_image);
        }
        $vendor->delete();
        return redirect()->route('vendor.staffmember.index')->with('success', 'Vendor deleted successfully');
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
