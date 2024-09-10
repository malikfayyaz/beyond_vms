@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div>
                <form action="{{ route('admin.admin-users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex-1">
                            <label for="first_name" class="block mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-1">
                            <label for="last_name" class="block mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block mb-2">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="profile_image" class="block mb-2">Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" class="w-full">
                        @error('profile_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 flex-1">
                        <label for="role" class="block mb-2">Role <span class="text-red-500">*</span></label>
                        <select id="role" name="role" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="" disabled selected>Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 flex-1">
                        <label for="country" class="block mb-2">Country <span class="text-red-500">*</span></label>
                        <select id="country" name="country" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="" disabled selected>Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4 flex-1">
                        <label for="status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Add Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
