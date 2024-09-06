<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends BaseController
{
/*    public function __construct()
    {
        // Apply middleware to specific methods
        $this->middleware('permission:job-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:job-view', ['only' => ['index', 'show']]);

        // Apply middleware to all methods except 'index' and 'show'
        $this->middleware('auth');
    }*/
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'user_type' => 'required', // Validate role name
        ]);
        Role::create([
            'name' => $request->input('name'),
            'user_type_id' => $request->input('user_type'),
        ]);

        return redirect()->back()->with('success', 'Role created successfully.');
    }



    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function assignRole(Request $request, User $user)
    {
        $user->syncRoles($request->roles);
        return redirect()->back()->with('success', 'Roles assigned successfully.');
    }
}

?>
