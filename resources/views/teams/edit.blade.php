@extends('layouts.app')

@section('content')
    <h1>Edit Team: {{ $team->name }}</h1>
    <form method="POST" action="{{ route('teams.update', $team->id) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $team->name }}">

        <h3>Select Users</h3>
        @foreach ($users as $user)
            <div>
                <input type="checkbox" name="users[]" value="{{ $user->id }}"
                       {{ in_array($user->id, $team->users->pluck('id')->toArray()) ? 'checked' : '' }}>
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

        <button type="submit">Update</button>
    </form>
@endsection
