@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')

        <div class="bg-white mx-4 my-8 rounded p-8">
            <h3 class="text-xl font-bold">Edit Admin</h3>
            <form action="{{ route('admin.admin-users.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex-1">
                        <label for="first_name" class="block mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $admin->first_name) }}" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                    </div>
                    <div class="flex-1">
                        <label for="last_name" class="block mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $admin->last_name) }}" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex-1">
                        <label for="email" class="block mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                    </div>
                    <div class="flex-1">
                        <label for="phone" class="block mb-2">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex-1">
                        <label for="role" class="block mb-2">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="" disabled>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $role->id == old('role', $admin->member_access) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="country" class="block mb-2">Country <span class="text-red-500">*</span></label>
                        <select id="country" name="country" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="" disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->id == old('country', $admin->country) ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label for="status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $admin->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="profile_image" class="block mb-2">Profile Image</label>
                    <input type="file" id="profile_image" name="profile_image" class="w-full px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                    @if ($admin->profile_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="Profile Image" class="w-32 h-32 object-cover">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
