@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('vendor.layouts.partials.header')

        <div class="bg-white mx-4 my-8 rounded p-8" x-data="vendorForm({{ $editIndex ?? 'null' }})">
            @include('vendor.layouts.partials.alerts') <!-- Include the partial view -->

            <!-- Conditional Heading -->
            @if(isset($editMode) && $editMode)
                <h1>Edit Team Member</h1>
            @else
                <h1>Add Team Member</h1>
            @endif

            <!-- Form Fields -->
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="first_name" class="block mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" id="first_name"  x-model="formData.first_name" class="w-full p-2 border rounded h-10">
                    <p x-show="firstNameError" class="text-red-500 text-sm mt-1" x-text="firstNameError"></p>
                </div>

                <div class="w-1/2 pl-2">
                    <label for="last_name" class="block mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" id="last_name" x-model="formData.last_name" class="w-full p-2 border rounded h-10">
                    <p x-show="lastNameError" class="text-red-500 text-sm mt-1" x-text="lastNameError"></p>
                </div>
            </div>

            <!-- Other Form Fields -->
            <div class="flex mb-4" >
                <div class="w-1/2 pr-2">
                    <label for="email" class="block mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="email" x-model="formData.email" class="w-full p-2 border rounded h-10" :disabled="editIndex !== null">
                    <p x-show="emailError" class="text-red-500 text-sm mt-1" x-text="emailError"></p>
                    <p id="error-message" class="hidden text-red-500"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="organization" class="block mb-2">Organization <span class="text-red-500">*</span></label>
                    <input type="text" id="organization" x-model="formData.organization" class="w-full p-2 border rounded h-10" :disabled="editIndex !== null">
                    <p x-show="organizationError" class="text-red-500 text-sm mt-1" x-text="organizationError"></p>
                </div>
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2">Phone Number<span class="text-red-500">*</span></label>
                <input type="text" id="phone" x-model="formData.phone" class="w-full p-2 border rounded h-10">
                <p x-show="phoneError" class="text-red-500 text-sm mt-1" x-text="phoneError"></p>
            </div>

            <div class="mb-4">
                <label for="profile_image" class="block mb-2">Profile Image</label>
                <input type="file" id="profile_image" x-model="formData.profile_image" class="w-full">
            </div>

            <div class="mb-4 flex-1">
                <label for="role" class="block mb-2">Role <span class="text-red-500">*</span></label>
                <select id="role" x-model="formData.role" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <p x-show="roleError" class="text-red-500 text-sm mt-1" x-text="roleError"></p>
            </div>

            <div class="mb-4 flex-1">
                <label for="country" class="block mb-2">Country <span class="text-red-500">*</span></label>
                <select id="country" x-model="formData.country" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <p x-show="countryError" class="text-red-500 text-sm mt-1" x-text="countryError"></p>
            </div>

            <div class="mb-4 flex-1">
                <label for="profile_status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                <select id="profile_status" x-model="formData.profile_status" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <p x-show="statusError" class="text-red-500 text-sm mt-1" x-text="statusError"></p>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="submitData()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    @if(isset($editMode) && $editMode)
                        Update Team Member
                    @else
                        Add Team Member
                    @endif
                </button>
            </div>
        </div>
    </div>

    <script>
        function vendorForm(editIndex) {
            return {
                formData: {
                    first_name: '{{ old('first_name', $vendor->first_name ?? '') }}',
                    last_name: '{{ old('last_name', $vendor->last_name ?? '') }}',
                    email: '{{ old('email', $vendor->user->email ?? '') }}',
                    phone: '{{ old('phone', $vendor->phone ?? '') }}',
                    profile_image: null,
                    role: '{{ old('role', $vendor->member_access ?? '') }}',
                    country: '{{ old('country', $vendor->country ?? '') }}',
                    profile_status: '{{ old('profile_status', $vendor->profile_status ?? '') }}',
                    organization: '{{ old('organization', $vendor->organization ?? '') }}',
                },

                searchTerm: '',
                editIndex: editIndex,
                currentUrl: '{{ url()->current() }}',

                firstNameError: '',
                lastNameError: '',
                emailError: '',
                roleError: '',
                countryError: '',
                statusError: '',
                phoneError: '',
                organizationError: '',

                validateFields() {
                    let errorCount = 0;

                    if (this.formData.first_name.trim() === "") {
                        this.firstNameError = "First name is required";
                        errorCount++;
                    } else {
                        this.firstNameError = '';
                    }

                    if (this.formData.last_name.trim() === "") {
                        this.lastNameError = "Last name is required";
                        errorCount++;
                    } else {
                        this.lastNameError = '';
                    }

                    if (this.formData.email.trim() === "") {
                        this.emailError = "Email address is required";
                        errorCount++;
                    } else {
                        this.emailError = '';
                    }

                    if (this.formData.organization.trim() === "") {
                        this.organizationError = "Organization is required";
                        errorCount++;
                    } else {
                        this.organizationError = '';
                    }

                    if (this.formData.phone.trim() === "") {
                        this.phoneError = "Phone address is required";
                        errorCount++;
                    } else {
                        this.phoneError = '';
                    }

                    if (this.formData.role === "") {
                        this.roleError = "Role is required";
                        errorCount++;
                    } else {
                        this.roleError = '';
                    }

                    if (this.formData.country === "") {
                        this.countryError = "Country is required";
                        errorCount++;
                    } else {
                        this.countryError = '';
                    }

                    if (this.formData.profile_status === "") {
                        this.statusError = "Status is required";
                        errorCount++;
                    } else {
                        this.statusError = '';
                    }

                    return errorCount === 0;
                },

                submitData() {
                    if (this.validateFields()) {
                        const formData = new FormData();
                        formData.append('first_name', this.formData.first_name);
                        formData.append('last_name', this.formData.last_name);
                        formData.append('email', this.formData.email);
                        formData.append('organization', this.formData.organization);
                        formData.append('phone', this.formData.phone);
                        if (this.profile_image) {
                            formData.append('profile_image', this.formData.profile_image);
                        }
                        formData.append('role', this.formData.role);
                        formData.append('country', this.formData.country);
                        formData.append('profile_status', this.formData.profile_status);

                        let url = '{{ route("vendor.staffmember.store") }}';
                        if (this.editIndex !== null) {

                            url = '{{ route("vendor.staffmember.update", ":id") }}';
                            url = url.replace(':id', editIndex);
                            formData.append('_method', 'PUT');
                            // alert(url);
                        }

                        ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
                    }
                },

                onSuccess(response) {
                    if (response.success && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else if (!response.success && response.redirect_url) {
                        document.getElementById('error-message').innerText = response.message;
                        document.getElementById('error-message').classList.remove('hidden');
                    }
                },

                cancelEdit() {
                    this.resetForm();
                    this.editIndex = null;
                },

                resetForm() {
                    this.formData.first_name = '';
                    this.formData.last_name = '';
                    this.formData.email = '';
                    this.formData.phone = '';
                    this.formData.profile_image = null;
                    this.formData.role = '';
                    this.formData.country = '';
                    this.formData.profile_status = '';
                }
            }
        }
    </script>
@endsection
