<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Location;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        if ($request->ajax()) {
            $data = User::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '
                    <a href="' . route('users.assignRoleForm', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent" title="Assign Roles & Permissions"
                     >
                    <i class="fas fa-tasks"></i>
                     </a>
';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index', compact('users'));
    }
    public function profile(User $user)
    {
        $user = Auth::user();
        $sessionrole = session('selected_role');
        if ($sessionrole == "admin"){
            $user = $user->admin;
            return view('users.profile', ['user' => $user, 'sessionrole' => $sessionrole]);
        }
        if ($sessionrole == "client") {
            $user = $user->client;
            return view('users.profile', ['sessionrole' => $sessionrole, 'user' => $user]);
        }
        if ($sessionrole == "vendor") {
            $user = $user->vendor;
            return view('users.profile', ['sessionrole' => $sessionrole, 'user' => $user]);
        }
        if ($sessionrole == "consultant") {
            $user = $user->consultant;
            return view('users.profile', ['sessionrole' => $sessionrole, 'user' => $user]);
        }
        return redirect()->route('users.profile')->with('error', 'Unauthorized access.');

    }
    public function profileUpdate(Request $request)
    {
 //       dd($filename);
        $userId = auth()->id();
        $sessionrole = session('selected_role');
        if ($request->isMethod('post')) {
            $commonRules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'description' => 'required',
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
            $roleSpecificRules = [];
            if ($sessionrole == 'admin') {
                $roleSpecificRules['username'] = 'required'; // Username is required for admin
            } elseif ($sessionrole == 'client') {
                $roleSpecificRules['middle_name'] = 'required';
                $roleSpecificRules['business_name'] = 'required';
                $roleSpecificRules['organization'] = 'required'; // Organization is required for client
            }
            // Merge common and role-specific rules
            $rules = array_merge($commonRules, $roleSpecificRules);
            $messages = [
                // Add custom messages here
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ]);
            }
            $validatedData = $validator->validated();
            // Initialize user model based on role
            $user = null;
            if ($sessionrole == 'admin') {
                $user = Admin::find($userId);
                $successMessage = 'Admin updated successfully!';
            } elseif ($sessionrole == 'client') {
                $user = Client::find($userId); // Assuming you have a Client model
                $successMessage = 'Client updated successfully!';
            }
            if ($user) {
                $filename = handleFileUpload($request, 'profile_image', 'profile_images');
                if ($filename) {
                    $validatedData['profile_image'] = $filename; // Store file path in the validated data
                }
                $user->update($validatedData); // Update the user with validated data
                session()->flash('success', $successMessage);
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect_url' => url()->current() // Redirect back URL for AJAX
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found!',
                ]);
            }
        }
    }


/*    public function profileUpdate(Request $request)
    {
        $sessionrole = session('selected_role');
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'description' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $userId = auth()->id();
        if ($sessionrole == 'client') {
            $request->validate([
                'middle_name' => 'required',
                'organization' => 'required',
                'business_name' => 'required',
            ]);
            $client = Client::find($userId);
            if ($client) {
                $client->first_name = $request->input('first_name');
                $client->middle_name = $request->input('middle_name');
                $client->last_name = $request->input('last_name');
                $client->organization = $request->input('organization');
                $client->business_name = $request->input('business_name');
                $client->profile_image = $request->input('profile_image');
                $client->description = $request->input('description');
                if ($request->hasFile('profile_image')) {
                    $originalName = $request->file('profile_image')->getClientOriginalName(); // Get the original file name
                    $filePath = $request->file('profile_image')->storeAs('profile_images', $originalName, 'public'); // Save with the original file name
                    $client->profile_image = $filePath;  // Store file path in the database
                }
                $client->save();
            }

        } elseif ($sessionrole == 'admin') {
            $request->validate([
                'username' => 'required',
            ]);
            $admin = Admin::find($userId);
            if ($admin) {
                $admin->first_name = $request->input('first_name');
                $admin->last_name = $request->input('last_name');
                $admin->username = $request->input('username');
                $admin->profile_image = $request->input('profile_image');
                $admin->description = $request->input('description');
                if ($request->hasFile('profile_image')) {
                    $originalName = $request->file('profile_image')->getClientOriginalName(); // Get the original file name
                    $filePath = $request->file('profile_image')->storeAs('profile_images', $originalName, 'public'); // Save with the original file name
                    $admin->profile_image = $filePath;  // Store file path in the database
                }
                $admin->save();
            }
        } elseif ($sessionrole == 'vendor') {
            $request->validate([
                'middle_name' => 'required',
                'organization' => 'required',
                'business_name' => 'required',
            ]);
            $vendor = Vendor::find($userId);
            if ($vendor) {
                $vendor->first_name = $request->input('first_name');
                $vendor->middle_name = $request->input('middle_name');
                $vendor->last_name = $request->input('last_name');
                $vendor->organization = $request->input('organization');
                $vendor->business_name = $request->input('business_name');
                $vendor->description = $request->input('description');
                if ($request->hasFile('profile_image')) {
                    $originalName = $request->file('profile_image')->getClientOriginalName(); // Get the original file name
                    $filePath = $request->file('profile_image')->storeAs('profile_images', $originalName, 'public'); // Save with the original file name
                    $vendor->profile_image = $filePath;  // Store file path in the database
                }
                $vendor->save();
            }
        }

        return redirect()->back()->with('success', 'Profile Updated successfully.');
    }*/


    //  // Show the form to assign roles and permissions
    //  public function showAssignRoleForm(User $user, Team $team)
    //  {
    //      $roles = Role::all();
    //      $permissions = Permission::all();

    //      return view('users.assign', compact('user', 'roles', 'permissions', 'team'));
    //  }

    // public function assignRolesAndPermissions(Request $request, User $user)
    // {
    //     $teamId = $request->input('team_id'); // Get the team_id from the request

    //     // Fetch roles by IDs
    //     $roles = Role::whereIn('id', $request->roles)->pluck('name')->toArray();

    //     // Sync roles with team context
    //     $user->syncRoles($roles, $teamId);

    //     // Fetch permissions by IDs (not scoped by team, typically)
    //     $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

    //     // Sync permissions
    //     $user->syncPermissions($permissions);

    //     return redirect()->route('users.index')->with('success', 'Roles and permissions assigned successfully.');
    // }

    public function assignRoleForm(User $user)
    {
        $user_type = getActiveRoles($user);
        $userTypeMapping = userType();
        $user_type_ids = [];
        foreach ($user_type as $role => $isActive) {
            if ($isActive) {
                $roleFormatted = ucfirst($role); // Convert 'admin' to 'Admin'
                $roleId = array_search($roleFormatted, $userTypeMapping);
                if ($roleId) {
                    $user_type_ids[] = $roleId; // Collect the corresponding user_type_id
                }
            }
        }
        $roles = Role::whereIn('user_type_id', $user_type_ids)->get();
        $permissions = Permission::all();

        return view('users.assign', compact('user', 'roles', 'permissions'));
    }



    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            // 'role' => 'required|exists:roles,name',
            'roles' => 'array',
            'permissions' => 'array',

        ]);

        //  dd($role);
        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        // Assign the permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->input('permissions'));
        }

         // Additionally, link permissions to the selected roles
         if ($request->has('roles') && $request->has('permissions')) {
            foreach ($request->input('roles') as $roleName) {
                $role = Role::findByName($roleName);
                $role->syncPermissions($request->input('permissions')); // Sync the permissions with the role
            }
        }



            return redirect()->back()->with('success', 'Role assigned successfully.');
        }

    public function assignPermissionForm(User $user)
    {
        $permissions = Permission::all();
        return view('users.assign-permission', compact('user', 'permissions'));
    }

    public function assignPermission(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->givePermissionTo($request->permission);

        return redirect()->back()->with('success', 'Permission assigned successfully.');
    }


}
