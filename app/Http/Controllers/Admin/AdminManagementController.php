<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCreated;
use App\Mail\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Country;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager load the role relationship
            $admins = Admin::with('role','user')->get();

            return datatables()->of($admins)
                ->addColumn('role', function($admin) {
                    return $admin->role ? $admin->role->name : 'N/A'; // Access the role name
                })
                ->addColumn('full_name', function ($admin) {
                    return $admin->full_name; // Use the accessor method to get full name
                })
                ->addColumn('email', function ($admin) {
                    return $admin->user->email; // Fetch email from the related User model
                })
                ->addColumn('admin_status', function ($admin) {
                    return $admin->admin_status == 1 ? 'Active' : 'Inactive'; // Check status and return text
                })
                ->addColumn('action', function($admin) {
                    return '<a href="'. route('admin.admin-users.show', $admin->id) .'" class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                        <i class="fas fa-eye"></i>
                    </a>

                    <a href="' . route('admin.admin-users.edit', $admin->id) . '"
                        class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                        >
                        <i class="fas fa-edit"></i>
                        </a>

                            <form action="'. route('admin.admin-users.destroy', $admin->id) .'" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.admin_users.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('user_type_id', 1)->get();
        $user = Admin::getAdminIdByUserId(Auth::id());
        $countries = Country::all();
        // dd($roles);

        return view('admin.users.admin_users.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $login_user = Admin::getAdminIdByUserId(Auth::id());
        $countries = Country::all();
        // Validation
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'member_access' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'admin_status' => 'required|boolean',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $request_email = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request_email)->first();
        if ($user) {

            $adminRecord = Admin::where('user_id', $user->id)->first();

            if ($adminRecord) {

                $errorMessage = 'An admin record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.admin-users.create') // Send back the URL to redirect to
                ]);

            } else {

                $validatedData['user_id'] = $user->id;

                $admin = Admin::create($validatedData);

                // Handle the profile image upload if provided
                if ($request->hasFile('profile_image')) {
                    $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                    $admin->profile_image = $imagePath;
                    $admin->save(); // Save after updating the profile image
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

            $admin = Admin::create($validatedData);

            //  Handle the profile image upload if provided

            if ($request->hasFile('profile_image')) {
                $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                $admin->profile_image = $imagePath;
                $admin->save(); // Save after updating the profile image
            }
        }

        $role = $validatedData['member_access'];

        $this->updateRoles($admin,$role);
        Mail::to($request_email)->send(new AdminCreated($admin, $user));
        $successMessage = 'Admin created successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("admin.admin-users.index")  // Redirect URL for AJAX
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the admin by ID
        $admin = Admin::findOrFail($id);

        // Return the view with the admin details
        return view('admin.users.admin_users.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $roles = Role::where('user_type_id', 1)->get();
        $countries = Country::all();

        return view('admin.users.admin_users.create', [
            'admin' => $admin,
            'roles' => $roles,
            'countries' => $countries,
            'editMode' => true ,
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
            'member_access' => 'required|exists:roles,id',
            'country' => 'required|exists:countries,id',
            'admin_status' => 'required|boolean',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin = Admin::findOrFail($id);

        $admin->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'member_access' => $validatedData['member_access'],
            'country' => $validatedData['country'],
            'admin_status' => $validatedData['admin_status'],
        ]);

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }

            // Upload new profile image
            $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
            if ($imagePath) {
                $admin->update(['profile_image' => $imagePath]); // Mass assign the image path
            }
        }

        $role = $validatedData['member_access'];

        $this->updateRoles($admin,$role);

        $successMessage = 'Admin updated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("admin.admin-users.index")  // Redirect URL for AJAX
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {

            $admin = Admin::findOrFail($id);
            $user = User::findOrFail($admin->user_id);


            if ($admin->profile_image && \Storage::disk('public')->exists($admin->profile_image)) {
                \Storage::disk('public')->delete($admin->profile_image);
            }

        // Delete the admin record
        $admin->delete();
            $user->syncRoles([]);
            $user->syncPermissions([]);

            $user->delete();
            $admin->delete();
        });


        // If not an AJAX request, redirect back with success message
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin deleted successfully');
    }

    public function updateRoles($admin, $role)
    {
        $userTypeId = 1;

        // Find the user based on the admin's user_id
        $user = User::findOrFail($admin->user_id);

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
