

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Assign Roles and Permissions to {{ $user->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
        @csrf

        

        <div class="form-group">
    <label for="roles">Roles</label>
    <div id="roles">
        @foreach($roles as $role)
            <div class="form-check">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}" 
                    class="form-check-input" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                <label class="form-check-label" for="role-{{ $role->id }}">
                    {{ $role->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="form-group">
    <label for="permissions">Permissions</label>
    <div id="permissions">
        @foreach($permissions as $permission)
            <div class="form-check">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $permission->id }}" 
                    class="form-check-input" {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                <label class="form-check-label" for="permission-{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>


        <button type="submit" class="btn btn-primary">Assign</button>
    </form>
</div>
@endsection

