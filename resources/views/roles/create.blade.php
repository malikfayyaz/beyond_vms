@extends('layouts.app')

@section('content')
    <h1>Create New Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Role Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
       
        <button type="submit">Create Role</button>
    </form>
@endsection
