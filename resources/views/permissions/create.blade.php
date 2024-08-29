@extends('layouts.app')

@section('content')
    <h1>Create Permission</h1>
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Permission Name">
        <button type="submit">Create</button>
    </form>
@endsection
