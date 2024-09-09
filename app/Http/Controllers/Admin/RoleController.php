<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends BaseController
{
    public function index(Request $request)
    {
        $roles = Role::all();
        if ($request->ajax()) {
            $data = Role::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="' . route('roles.assignPermissionForm', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent" title="Assign Permissions"
                     >
                    <i class="fas fa-tasks"></i>
                     </a>
                     <a href="' . route('roles.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent" title="Edit Role"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('roles.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent" >
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn .$deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // Logic to get and display catalog items
        return view('roles.index', compact('roles')); // Assumes you have a corresponding Blade view
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
    public function assignPermissionForm(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.assign-permission', compact('role', 'permissions'));
    }
    public function assignPermission(Request $request, Role $role)
    {
        $request->validate([
            'roles' => 'array',
            'permissions' => 'array',

        ]);
        if (!empty($request)){
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }
}

?>
