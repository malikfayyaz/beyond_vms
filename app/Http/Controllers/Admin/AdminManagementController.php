<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Country;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager load the role relationship
            $admins = Admin::with('role')->select(['id', 'first_name', 'last_name', 'email', 'status', 'member_access'])->get();
            
            return datatables()->of($admins)
                ->addColumn('role', function($admin) {
                    return $admin->role ? $admin->role->name : 'N/A'; // Access the role name
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
        $user = Auth::user();
        $countries = Country::all();
        // dd($roles);
        
        return view('admin.users.admin_users.create', compact('roles', 'countries'));
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
            'email' => 'required|email|unique:admins,email',
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

        $email_found = User::where('email', $email)->first();
       
        if ($email_found) {
        
            $adminRecord = Admin::where('user_id', $email_found->id)->first();
            
            if ($adminRecord) {

                $errorMessage = 'An admin record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.admin-users.create') // Send back the URL to redirect to
                ]);

            } else {
                 
                $admin = new Admin;
                $admin->user_id = $email_found->id;
                $admin->first_name = $first_name;
                $admin->last_name = $last_name;
                $admin->email = $email;
                $admin->phone = $phone;
                $admin->member_access = $role;
                $admin->admin_status = 1;
                $admin->country = $country; // Assuming you have a foreign key to countries
                $admin->status = $status;

                // Handle the profile image upload if provided
                $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                if ($imagePath) {
                    $admin->profile_image = $imagePath;
                }

                $admin->save();
            }
        } else {
            
            $user = new User;      
            $user->name = $first_name;
            $user->email = $email;
            $user->password = Hash::make('password');
            $user->is_admin = 1;

            $user->save();

            $admin = new Admin;
            $admin->user_id = $user->id;
            $admin->first_name = $first_name;
            $admin->last_name = $last_name;
            $admin->email = $email;
            $admin->phone = $phone;
            $admin->member_access = $role; 
            $admin->admin_status = 1;
            $admin->country = $country; 
            $admin->status = $status;

            //  Handle the profile image upload if provided

            $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
            if ($imagePath) {
                $admin->profile_image = $imagePath;
            }

            $admin->save();

            $roles_permission = DB::table('role_has_permissions')->where('role_id', $role)->get();
            foreach($roles_permission as $permission){
                $insert = DB::table('model_has_permissions')->insert([
                    'model_id' => $user->id, // Associating with the user
                    'permission_id' => $permission->permission_id, // Adjust field as per your table structure
                    'model_type' => 'App\Models\User', // Assuming you're using the User model
                ]);
            }

            $roles = DB::table('roles')->where('id', $role)->get(); // Assuming you're getting role from request
            foreach ($roles as $roleData) {
                $insert = DB::table('model_has_roles')->insert([
                    'role_id' => $roleData->id, // Role ID from the roles table
                    'model_id' => $user->id, // Associating the role with the user
                    'model_type' => 'App\Models\User', // The model being associated, usually 'User'
                ]);
            }            
        }

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
            'email' => 'required|email|unique:admins,email,' . $id,
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

        $admin = Admin::findOrFail($id);
        $admin->first_name = $first_name;
        $admin->last_name = $last_name;
        // $admin->email = $request->email;
        $admin->phone = $phone;
        $admin->member_access = $role;
        $admin->country = $country;
        $admin->status = $status;

        // Handle the profile image upload if provided
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }

            $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
            if ($imagePath) {
                $admin->profile_image = $imagePath;
            }
        }
        

        $admin->save();

        DB::table('model_has_permissions')->where('model_id', $admin->user_id)->where('model_type', 'App\Models\User')->delete();
        DB::table('model_has_roles')->where('model_id', $admin->user_id)->where('model_type', 'App\Models\User')->delete();

        // Assign new permissions
        $roles_permission = DB::table('role_has_permissions')->where('role_id', $role)->get();
        foreach ($roles_permission as $permission) {
            DB::table('model_has_permissions')->insert([
                'model_id' => $admin->user_id, // Associating with the user
                'permission_id' => $permission->permission_id, // Adjust field as per your table structure
                'model_type' => 'App\Models\User', // Assuming you're using the User model
            ]);
        }

        // Assign new roles
        $roles = DB::table('roles')->where('id', $role)->get(); 
        foreach ($roles as $roleData) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleData->id, // Role ID from the roles table
                'model_id' => $admin->user_id, // Associating the role with the user
                'model_type' => 'App\Models\User', // The model being associated, usually 'User'
            ]);
        }


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
        // Find the admin by ID
        $admin = Admin::findOrFail($id);

        // Check if the admin has a profile image and delete the file if it exists
        if ($admin->profile_image && \Storage::exists('public/' . $admin->profile_image)) {
            \Storage::delete('public/' . $admin->profile_image);
        }

        // Delete the admin record
        $admin->delete();


        // If not an AJAX request, redirect back with success message
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin deleted successfully');
    }

}
