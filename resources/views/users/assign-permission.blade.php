@extends('layouts.app')

@section('content')
    <h1>Assign Permission to {{ $user->name }}</h1>
    <form action="{{ route('users.assignPermission', $user->id) }}" method="POST">
        @csrf
        <div>
            <label for="permission">Permission:</label>
            <select name="permission" id="permission" required>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="team_id">Team ID:</label>
            <input type="number" name="team_id" id="team_id" required>
        </div>
        <button type="submit">Assign Permission</button>
    </form>
@endsection
