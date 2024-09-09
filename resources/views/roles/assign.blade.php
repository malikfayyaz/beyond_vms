@extends('layouts.app')

@section('content')
    <h1>Assign Roles to User</h1>
    <form method="POST" action="{{ route('roles.assign', $user->id) }}">
        @csrf

        <h3>Select Roles</h3>
        @foreach ($roles as $role)
            <div>
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                       {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                {{ $role->name }}
            </div>
        @endforeach

        <button type="submit">Assign Roles</button>
    </form>
@endsection
