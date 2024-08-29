@extends('layouts.app')

@section('content')
    <h1>Edit Permission: {{ $permission->name }}</h1>
    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $permission->name }}">
        <button type="submit">Update</button>
    </form>
@endsection
