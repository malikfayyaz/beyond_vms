<?php

namespace App\Http\Controllers\Admin;
use App\Mail\ClientCreated;
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
use Illuminate\Support\Facades\Mail;
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
                ->addColumn('role', function ($row) {
                    $userTypeId = 2;
                    $existing_role = User::with(['roles' => function ($query) use ($userTypeId) {
                        $query->where('user_type_id', 2)
                            ->select('id', 'name', 'user_type_id'); // Select specific columns
                    }])
                        ->findOrFail($row->user_id);
                    $alreadyroles = '';
                    foreach($existing_role->roles as $rolesvalue) {
                        $alreadyroles .=$rolesvalue->name;
                    }
                    return $alreadyroles;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email; // Fetch email from the related User model
                })
                ->addColumn('profile_status', function ($row) {
                    // Assuming 'profile_status' represents the status
                    return $row->profile_status == 1 ? 'Active' : 'Inactive';  // Convert status to readable format
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
        $user = Auth::user();
        $countries = Country::all();
        $editIndex = null;
//         dd($roles);

        return view('admin.users.client-users.create', compact('roles', 'user', 'countries', 'editIndex'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $commonRules = [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'required|email:users,email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'phone' => 'required',
            'country' => 'required|exists:countries,id',
            'profile_status' => 'required',
            'role' => 'required',
        ];
        $roleSpecificRules = [
            'middle_name' => 'required',
            'business_name' => 'required',
            'organization' => 'required',
        ];
        $rules = array_merge($commonRules, $roleSpecificRules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }
        $validatedData = $validator->validated();
        $email = $validatedData['email'];
        $role = $validatedData['role'];
        $user = User::where('email', $email)->first();
        if ($user) {
            $userId = $user->id;
            $existingClient = Client::where('user_id', $userId)->first();
            if ($existingClient) {
                $errorMessage = 'A Client record already exists for this email.';
                session()->flash('error', $errorMessage);
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'redirect_url' => route('admin.client-users.create')
                ]);
            } else {
                // Unset email from validated data before creating the client
                unset($validatedData['email']);
                unset($validatedData['role']);
                $validatedData['user_id'] = $user->id;
                $client = Client::create($validatedData);

                if ($request->hasFile('profile_image')) {
                    $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                    $client->profile_image = $imagePath;
                    $client->save();
                }
            }
        } else {
            $userData = [
                'name' => $validatedData['first_name'],
                'email' => $email, // inserting the email only for the user
                'password' => Hash::make('password'),
                'is_client' => 1,
            ];
            $user = User::create($userData);            // Create a new user
            $validatedData['user_id'] = $user->id;
            unset($validatedData['email']);
            unset($validatedData['role']);
            $client = Client::create($validatedData);
            if ($request->hasFile('profile_image')) {
                $imagePath = handleFileUpload($request, 'profile_image', 'admin_profile');
                $client->profile_image = $imagePath;
            }
            $client->save();
        }
        $this->updateRoles($client, $role);
/*        dump($email);
        dump($client);
        dd('here');*/
        Mail::to($email)->send(new ClientCreated($client, $user, 'password'));
        session()->flash('success', 'Client created successfully!');
        return response()->json(['success' => true, 'redirect_url' => route('admin.client-users.index')]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        $user = User::find($client->user_id);
        $userTypeId = 2;
        $existing_role = User::with(['roles' => function ($query) use ($userTypeId) {
            $query->where('user_type_id', 2)->select('id', 'name', 'user_type_id');
        }])->findOrFail($client->user_id);
        $alreadyroles = '';
        foreach($existing_role->roles as $rolesvalue) {
            $alreadyroles .=$rolesvalue->name;
        }
        return view('admin.users.client-users.show', compact('client', 'user', 'alreadyroles'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        $user = User::find($client->user_id);
        $roles = Role::where('user_type_id', 2)->get();
        $countries = Country::all();
//        dd($client);
        return view('admin.users.client-users.create', [
            'user' => $user,
            'client' => $client,
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
        $userId = auth()->id();
        $commonRules = [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'required|integer|between:0,255|exists:countries,id',
            'description' => 'required',
            'profile_status' => 'required',
        ];
        $roleSpecificRules = [
            'middle_name' => 'required',
            'business_name' => 'required',
            'organization' => 'required',
        ];
        $rules = array_merge($commonRules, $roleSpecificRules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }
        $filename = handleFileUpload($request, 'profile_image', 'profile_images');
        $validatedData = $validator->validated();
        if ($filename) {
            $validatedData['profile_image'] = $filename;
        }
        $client = Client::find($id);
        $user = User::find($client->user_id);
        $validatedData['profile_approved_date'] = Carbon::now();
        $validatedData['user_id'] = $user->id;
        $validatedData['manager_id'] = '1';
        $client->fill($validatedData);
        $client->save();
        $this->updateRoles($client,$request->role);
        session()->flash('success', 'Client updated successfully!');
        return response()->json(['success' => true, 'redirect_url' => route('admin.client-users.index')]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        if ($client->profile_image && \Storage::exists('public/' . $client->profile_image)) {
            \Storage::delete('public/' . $client->profile_image);
        }
        $client->delete();
        return redirect()->route('admin.client-users.index')->with('success', 'Client deleted successfully');
    }
    public function updateRoles($client, $role) {
        $userTypeId = 2;

        // Fetch the user by ID
        $user = User::findOrFail($client->user_id);

        // Retrieve existing roles for the user
        $existing_role = $user->with(['roles' => function ($query) use ($userTypeId) {
            $query->where('user_type_id', $userTypeId)
                ->select('id', 'name', 'user_type_id');
        }])->findOrFail($client->user_id);

        // Collect currently assigned roles
        $alreadyroles = $existing_role->roles->pluck('name')->toArray();

        // Determine if $role is an ID or a name
        if (is_numeric($role)) {
            $roleModel = \Spatie\Permission\Models\Role::find($role);
            if ($roleModel) {
                $newUpdatedRole = $roleModel->name;
            } else {
                return response()->json(['error' => 'Role ID does not exist.'], 404);
            }
        } else {
            $newUpdatedRole = $role;
        }

        // Remove roles that are not in the new role set
        foreach ($alreadyroles as $roles) {
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
