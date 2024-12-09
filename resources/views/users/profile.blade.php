@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @if($sessionrole === 'vendor')
        @include('vendor.layouts.partials.dashboard_side_bar')
    @elseif($sessionrole === 'client')
        @include('client.layouts.partials.dashboard_side_bar')
    @else
        @include('admin.layouts.partials.dashboard_side_bar')
    @endif
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @if($sessionrole === 'vendor')
            @include('vendor.layouts.partials.header')
        @elseif($sessionrole === 'client')
            @include('client.layouts.partials.header')
        @else
            @include('admin.layouts.partials.header')
        @endif
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="profileForm()" enctype="multipart/form-data">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Profile {{ucfirst($sessionrole)}}</h2>
                @php
                    /*                dump($sessionrole);
                                    dump($user);*/
                    /*                        $user_types = userType();*/
                @endphp
            </div>
            <div class="p-8 bg-sky-100 rounded w-full">
                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="first_name" class="block mb-2">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            x-model="first_name"
                            required
                            class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                        />
                    </div>
                    @if($user->middle_name)
                        <div class="w-1/2 pl-2">
                            <label for="middle_name" class="block mb-2">Middle Name</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="middle_name"
                                    x-model="middle_name"
                                    required
                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                />
                                <p x-show="nameError" class="text-red-500 text-sm mt-1" x-text="nameError"></p>
                            </div>
                        </div>
                    @endif
                    <div class="w-1/2 pl-2">
                        <label for="last_name" class="block mb-2">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            x-model="last_name"
                            required
                            class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                        />
                    </div>
                    </div>
                <div class="flex mb-4">
                    @if($user->username)
                        <div class="w-1/2 pr-2">
                            <label for="username" class="block mb-2">User Name</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="username"
                                    x-model="username"
                                    required
                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                />
{{--                                <p x-show="nameError" class="text-red-500 text-sm mt-1" x-text="nameError"></p>--}}
                            </div>
                        </div>
                    @endif
                        <div class="flex-1">
                            <label for="profile_image" class="block mb-2">Profile Image</label>
                            <input name="profile_image" type="file" id="profile_image" name="profile_image" @change="handleFileUpload"
                                   class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                           @if($user->profile_image)
                                <p>Current File: {{ basename($user->profile_image) }}</p> <!-- Display the file name -->
                            @endif
{{--                            <p x-show="profileImageError" class="text-red-500 text-sm mt-1" x-text="profileImageError"></p>--}}
                        </div>
                </div>
                <div class="flex space-x-4 mt-4">
                    @if($user->organization)
                        <div class="flex-1">
                            <label for="organization" class="block mb-2">Organization</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="organization"
                                    x-model="organization"
                                    required
                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                />
                            </div>
                        </div>
                    @endif
                    @if($user->business_name)
                        <div class="flex-1">
                            <label for="business_name" class="block mb-2">Business Name</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="business_name"
                                    x-model="business_name"
                                    required
                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                />
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex space-x-4 mt-4">
                    @if($user->description)
                        <div class="flex-1">
                            <label for="description" class="block mb-2">Description</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="description"
                                    x-model="description"
                                    name="description"
                                    value="{{ $user->description }}"
                                    required
                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                />
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    <button @click="submitData()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">
                        <span x-text="'Update'"></span>
                        Profile
                    </button>
                </div>
            </div>
        </div>
            <script>
            function profileForm() {
                return {
                    first_name: "{{ $user->first_name }}",
                    middle_name: "{{ $user->middle_name }}",
                    last_name: "{{ $user->last_name }}",
                    username: "{{ $user->username }}",
                    organization: "{{ $user->organization }}",
                    business_name: "{{ $user->business_name }}",
                    profile_image: "{{ $user->profile_image }}",
                    description: "{{ $user->description }}",
                    attachmentFile: null,
                    handleFileUpload(event) {
                        this.attachmentFile = event.target.files[0]; // Store the selected file
                    },
                    submitData() {
                        const formData = new FormData();
                        formData.append('first_name', this.first_name);
                        formData.append('middle_name', this.middle_name);
                        formData.append('last_name', this.last_name);
                        formData.append('username', this.username);
                        formData.append('organization', this.organization);
                        formData.append('business_name', this.business_name);
                        formData.append('description', this.description);
                        if (this.attachmentFile) {
                            formData.append("profile_image", this.attachmentFile);
                        }
                        ajaxCall('{{ route('users.profileUpdate') }}', 'POST', [[onSuccess, ['response']]], formData);
                    }
                }
            }
            </script>
@endsection
