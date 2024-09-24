@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <h3 class="text-xl font-bold">Client Details - {{ $client->full_name }}</h3>

            <div class="mb-4">
                <strong>First Name:</strong> {{ $client->first_name }}
            </div>
            <div class="mb-4">
                <strong>Middle Name:</strong> {{ $client->middle_name }}
            </div>

            <div class="mb-4">
                <strong>Last Name:</strong> {{ $client->last_name }}
            </div>

            <div class="mb-4">
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <div class="mb-4">
                <strong>Business Name:</strong> {{ $client->business_name ?? 'N/A' }}
            </div>
            <div class="mb-4">
                <strong>Organization:</strong> {{ $client->organization ?? 'N/A' }}
            </div>
            <div class="mb-4">
                <strong>Description:</strong> {{ $client->description ?? 'N/A' }}
            </div>
            <div class="mb-4">
                <strong>Role:</strong> {{ $alreadyroles ?? 'N/A' }}
            </div>
            {{--            <div class="mb-4">
                            <strong>Role:</strong>
                            @if($client->roles->isNotEmpty())
                                {{ $client->roles->first()->name }}
                            @else
                                N/A
                            @endif
                        </div>--}}
            <div class="mb-4">
                <strong>Status:</strong>
                @if($client->profile_status == 1)
                    Active
                @else($client->profile_status == 0)
                    InActive
                @endif
            </div>
            @if($client->profile_image)
                <div class="mb-4">
                    <strong>Profile Image:</strong><br>
                    <img src="{{ asset('storage/' . $client->profile_image) }}" alt="Profile Image" width="150" class="rounded">
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.client-users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Back to List</a>
            </div>
        </div>
    </div>
@endsection
