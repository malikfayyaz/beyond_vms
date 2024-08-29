@extends('layouts.app')

@section('content')
    <h1>Teams</h1>
    <a href="{{ route('teams.create') }}">Create Team</a>
    <ul>
        @foreach ($teams as $team)
            <li>{{ $team->name }} - <a href="{{ route('teams.edit', $team->id) }}">Edit</a></li>
        @endforeach
    </ul>
@endsection
