@extends('layouts.app')

@section('content')
    <h1>Assign Roles to Users in Team</h1>
    <form method="POST" action="{{ route('teams.assign', $team->id) }}">
        @csrf
        
        <h3>Select Users</h3>
        @foreach ($users as $user)
            <div>
                <input type="checkbox" name="users[]" value="{{ $user->id }}"
                       {{ $team->users->contains($user->id) ? 'checked' : '' }}>
                {{ $user->name }}
            </div>
        @endforeach
        
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
