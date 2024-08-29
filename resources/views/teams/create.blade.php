@extends('layouts.app')

@section('content')
    <h1>Create Team</h1>
    <form method="POST" action="{{ route('teams.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Team Name">
        <button type="submit">Create</button>
    </form>
@endsection
