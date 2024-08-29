@extends('layouts.app')

@section('content')
    <h1>Roles</h1>
    <a href="{{ route('roles.create') }}">Create Role</a>
    <ul>
        @foreach ($roles as $role)
            <li>{{ $role->name }} - <a href="{{ route('roles.edit', $role->id) }}">Edit</a></li>
        @endforeach
    </ul>
@endsection
