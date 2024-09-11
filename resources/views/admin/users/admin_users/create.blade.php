@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="adminUser()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->

            <!-- Conditional Heading -->
            @if(isset($editMode) && $editMode)
                <h1>Edit Admin</h1>
            @else
                <h1>Create Admin</h1>
            @endif

            <!-- Form Fields -->
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="first_name" class="block mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" id="first_name" x-model="first_name" class="w-full p-2 border rounded h-10">
                    <p x-show="firstNameError" class="text-red-500 text-sm mt-1" x-text="firstNameError"></p>
                </div>

                <div class="w-1/2 pl-2">
                    <label for="last_name" class="block mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" id="last_name" x-model="last_name" class="w-full p-2 border rounded h-10">
                    <p x-show="lastNameError" class="text-red-500 text-sm mt-1" x-text="lastNameError"></p>
                </div>
            </div>

            <!-- Other Form Fields -->
            <div class="mb-4">
                <label for="email" class="block mb-2">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" x-model="email" class="w-full p-2 border rounded h-10">
                <p x-show="emailError" class="text-red-500 text-sm mt-1" x-text="emailError"></p>
                <p id="error-message" class="hidden text-red-500"></p>
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2">Phone Number</label>
                <input type="text" id="phone" x-model="phone" class="w-full p-2 border rounded h-10">
            </div>

            <div class="mb-4">
                <label for="profile_image" class="block mb-2">Profile Image</label>
                <input type="file" id="profile_image" x-model="profile_image" class="w-full">
            </div>

            <div class="mb-4 flex-1">
                <label for="role" class="block mb-2">Role <span class="text-red-500">*</span></label>
                <select id="role" x-model="role" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <p x-show="roleError" class="text-red-500 text-sm mt-1" x-text="roleError"></p>
            </div>

            <div class="mb-4 flex-1">
                <label for="country" class="block mb-2">Country <span class="text-red-500">*</span></label>
                <select id="country" x-model="country" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <p x-show="countryError" class="text-red-500 text-sm mt-1" x-text="countryError"></p>
            </div>

            <div class="mb-4 flex-1">
                <label for="status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                <select id="status" x-model="status" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <p x-show="statusError" class="text-red-500 text-sm mt-1" x-text="statusError"></p>
            </div>

            <div class="flex justify-end">
                <button type="button" @click="submitData()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    @if(isset($editMode) && $editMode)
                        Update Admin
                    @else
                        Add Admin
                    @endif
                </button>
            </div>
        </div>
    </div>

    <script>
    function adminUser() {
        return {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            profile_image: null,
            role: '',
            country: '',
            status: '',
              // Assuming $data contains user data for listing/editing
            searchTerm: '',
            editIndex: null,
            currentUrl: '{{ url()->current() }}',
            
            // Error messages
            firstNameError: '',
            lastNameError: '',
            emailError: '',
            roleError: '',
            countryError: '',
            statusError: '',

            // Validation
            validateFields() {
                let errorCount = 0;

                if (this.first_name.trim() === "") {
                    this.firstNameError = "First name is required";
                    errorCount++;
                } else {
                    this.firstNameError = '';
                }

                if (this.last_name.trim() === "") {
                    this.lastNameError = "Last name is required";
                    errorCount++;
                } else {
                    this.lastNameError = '';
                }

                if (this.email.trim() === "") {
                    this.emailError = "Email address is required";
                    errorCount++;
                } else {
                    this.emailError = '';
                }

                if (this.role === "") {
                    this.roleError = "Role is required";
                    errorCount++;
                } else {
                    this.roleError = '';
                }

                if (this.country === "") {
                    this.countryError = "Country is required";
                    errorCount++;
                } else {
                    this.countryError = '';
                }

                if (this.status === "") {
                    this.statusError = "Status is required";
                    errorCount++;
                } else {
                    this.statusError = '';
                }

                return errorCount === 0;  // Return true if no errors
            },

            // Submit form data
            submitData() {
                if (this.validateFields()) {
                    const formData = new FormData();
                    formData.append('first_name', this.first_name);
                    formData.append('last_name', this.last_name);
                    formData.append('email', this.email);
                    formData.append('phone', this.phone);
                    if (this.profile_image) {
                        formData.append('profile_image', this.profile_image);
                    }
                    formData.append('role', this.role);
                    formData.append('country', this.country);
                    formData.append('status', this.status);
                    
                    // Handle edit case
                    if (this.editIndex !== null) {
                        formData.append('id', this.items[this.editIndex].id);
                    }

                    let url = '{{ route("admin.admin-users.store") }}';
                    // Send data via AJAX
                    ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);

                    this.cancelEdit();
                }
            },

            // Success handler
            onSuccess(response) {
                console.log("Form submitted successfully", response);
                
                // Handle success case
                if (response.success && response.redirect_url) {
                    window.location.href = response.redirect_url; // Redirect to the URL specified in the response
                } 
                // Handle error case
                else if (!response.success && response.redirect_url) {
                    // alert(response.message); // You can replace this with a custom error display logic
                    // window.location.href = response.redirect_url; // Redirect to the create route or reload the page
                    document.getElementById('error-message').innerText = response.message;
                    document.getElementById('error-message').classList.remove('hidden');
                }
            },

            // Cancel edit
            cancelEdit() {
                this.resetForm();
                this.editIndex = null;
            },

            // Reset form
            resetForm() {
                this.first_name = '';
                this.last_name = '';
                this.email = '';
                this.phone = '';
                this.profile_image = null;
                this.role = '';
                this.country = '';
                this.status = '';
                this.clearErrors();
            },

            // Clear errors
            clearErrors() {
                this.firstNameError = '';
                this.lastNameError = '';
                this.emailError = '';
                this.roleError = '';
                this.countryError = '';
                this.statusError = '';
            },

            // Edit item
            editItem(item) {
                this.editIndex = this.items.indexOf(item);
                this.first_name = item.first_name;
                this.last_name = item.last_name;
                this.email = item.email;
                this.phone = item.phone;
                this.role = item.role;
                this.country = item.country;
                this.status = item.status;
                this.clearErrors();
            },

            // Filtered items based on search term
            get filteredItems() {
                return this.items.filter(item => {
                    return item.first_name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                           item.last_name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                           item.email.toLowerCase().includes(this.searchTerm.toLowerCase());
                });
            }
        }
    }

</script>

@endsection
