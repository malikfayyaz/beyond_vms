@extends('layouts.app')

@section('content')
    <h1>Users</h1>
   
    <ul>
        @foreach ($users as $user)
            <li>
                
                {{ $user->name }} - <a href="{{ route('users.assignRoleForm', [ 'user' => $user->id]) }}">
                    Assign Roles & Permissions
                </a>
            </li>
        @endforeach
    </ul>
@endsection
