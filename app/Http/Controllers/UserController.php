<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

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
