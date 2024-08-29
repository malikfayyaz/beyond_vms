@extends('layouts.app')

@section('content')
    <h1>Edit Role: {{ $role->name }}</h1>
    <form method="POST" action="{{ route('roles.update', $role->id) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $role->name }}">
        <button type="submit">Update</button>
    </form>
@endsection
