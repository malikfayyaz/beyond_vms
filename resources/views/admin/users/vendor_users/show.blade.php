@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')

        <div class="bg-white mx-4 my-8 rounded p-8">
            <h3 class="text-xl font-bold">Vendor Details</h3>
            
            <div class="mb-4">
                <strong>First Name:</strong> {{ $vendor->first_name }}
            </div>

            <div class="mb-4">
                <strong>Last Name:</strong> {{ $vendor->last_name }}
            </div>

            <div class="mb-4">
                <strong>Email:</strong> {{ $vendor->user->email }}
            </div>

            <div class="mb-4">
                <strong>Phone:</strong> {{ $vendor->phone ?? 'N/A' }}
            </div>

            <div class="mb-4">
                <strong>Role:</strong> {{ $vendor->role->name ?? 'N/A' }}
            </div>

            <div class="mb-4">
                <strong>Status:</strong> {{ ucfirst($vendor->status) }}
            </div>

            @if($vendor->profile_image)
            <div class="mb-4">
                <strong>Profile Image:</strong><br>
                <img src="{{ asset('storage/' . $vendor->profile_image) }}" alt="Profile Image" width="150" class="rounded">
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.vendor-users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Back to List</a>
            </div>
        </div>
    </div>
@endsection
