@extends('layouts.app')

@section('content')
    <h1>Permissions</h1>
    <a href="{{ route('permissions.create') }}">Create Permission</a>
    <ul>
        @foreach ($permissions as $permission)
            <li>{{ $permission->name }} - <a href="{{ route('permissions.edit', $permission->id) }}">Edit</a></li>
        @endforeach
    </ul>
@endsection
