@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="locationManager()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->
            
            <h1>Location</h1>
            
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="name" class="block mb-2">Location Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" x-model="name" class="w-full p-2 border rounded h-10">
                    <p x-show="nameError" class="text-red-500 text-sm mt-1" x-text="nameError"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="zip_code" class="block mb-2">Zip Code <span class="text-red-500">*</span></label>
                    <input type="text" name="zip_code" id="zip_code" x-model="zip_code" class="w-full p-2 border rounded h-10">
                    <p x-show="zip_codeError" class="text-red-500 text-sm mt-1" x-text="zip_codeError"></p>
                </div>
            </div>
            
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="country-select" class="block mb-2">Country <span class="text-red-500">*</span></label>
                    <select id="country-select" name="country_id" x-model="country_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="country_idError" class="text-red-500 text-sm mt-1" x-text="country_idError"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="state-select" class="block mb-2">State <span class="text-red-500">*</span></label>
                    <select id="state-select" name="state_id" x-model="state_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select State</option>
                       
                    </select>
                    <p x-show="state_idError" class="text-red-500 text-sm mt-1" x-text="state_idError"></p>
                </div>
            </div>
            
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="city" class="block mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" x-model="city" class="w-full p-2 border rounded h-10">
                    <p x-show="cityError" class="text-red-500 text-sm mt-1" x-text="cityError"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="address1" class="block mb-2">Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address1" id="address1" x-model="address1" class="w-full p-2 border rounded h-10">
                    <p x-show="address1Error" class="text-red-500 text-sm mt-1" x-text="address1Error"></p>
                </div>
              
            </div>

            <div class="flex mb-4">
              
                <div class="w-1/2 pl-2">
                    <label for="status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" x-model="status" class="w-full p-2 border rounded h-10 bg-white">
                                <option value="" disabled>Select</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                    </select>
                    <p x-show="statusError" class="text-red-500 text-sm mt-1" x-text="statusError"></p>
                </div>
            </div>

            <div class="flex mb-4">
                <button @click="submitData()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">
                    <span x-text="editIndex !== null ? 'Update' : 'Add'"></span>
                </button>
                <button @click="cancelEdit()" class="bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            </div>

            <div class="mb-4">
                <input type="text" x-model="searchTerm" placeholder="Search Location" class="w-full p-2 border rounded">
            </div>

            <table class="w-full border-collapse border">
                <thead>
                    <tr>
                        <th class="border p-2">Sr #</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Country</th>
                        <th class="border p-2">State</th>
                        <th class="border p-2">City</th>
                        <th class="border p-2">Zip Code</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in filteredItems" :key="index">
                        <tr>
                            <td class="border p-2 text-center" x-text="index + 1"></td>
                            <td class="border p-2 text-center" x-text="item.name"></td>
                            <td class="border p-2 text-center" x-text="item.status"></td>
                            <td class="border p-2 text-center" x-text="item.country.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.state.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.city"></td>
                            <td class="border p-2 text-center" x-text="item.zip_code"></td>
                            <td class="border p-2 text-center">
                                <span @click="editItem(item)" class="text-gray-600 cursor-pointer">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <script>
               
            function locationManager() {
                return {
                    name: "",
                    status: "",
                    country_id: "",
                    state_id: "",
                    city: "",
                    zip_code: "",
                    address1: "",
                    nameError: "",
                    statusError: "",
                    country_idError: "",
                    state_idError: "",
                    address1Error: "",
                    cityError: "",
                    zip_codeError: "",
                    items: @json($data),
                    searchTerm: "",
                    editIndex: null,
                    error: 0,
                    currentUrl: `{{ url()->current() }}`,

                    validateFields() {
                        this.error = 0;
                        if (this.name.trim() === "") {
                            this.nameError = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.nameError = "";
                        }

                        if (this.status.trim() === "") {
                            this.statusError = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.statusError = "";
                        }

                        if (this.country_id === "") {
                            this.country_idError = "Please select a country";
                            this.error += 1;
                        } else {
                            this.country_idError = "";
                        }

                        if (this.state_id === "") {
                            this.state_idError = "Please select a state";
                            this.error += 1;
                        } else {
                            this.state_idError = "";
                        }

                        if (this.city.trim() === "") {
                            this.cityError = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.cityError = "";
                        }

                        if (this.zip_code.trim() === "") {
                            this.zip_codeError = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.zip_codeError = "";
                        }
                        if (this.address1.trim() === "") {
                            this.address1Error = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.address1Error = "";
                        }
                    },

                    submitData() {
                        this.validateFields();

                        if (this.error == 0) {
                            const formData = new FormData();
                            formData.append('name', this.name);
                            formData.append('status', this.status);
                            formData.append('country_id', this.country_id);
                            formData.append('state_id', this.state_id);
                            formData.append('city', this.city);
                            formData.append('zip_code', this.zip_code);
                            formData.append('address1', this.address1);
                            if (this.editIndex !== null) {
                                formData.append('id', this.items[this.editIndex].id);
                            }
                            
                            let url = this.currentUrl;
                            ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                            this.cancelEdit();
                        }
                    },

                    cancelEdit() {
                        this.name = '';
                        this.status = '';
                        this.country_id = '';
                        this.state_id = '';
                        this.city = '';
                        this.zip_code = '';
                        this.address1 = '';
                        this.editIndex = null;
                        this.clearErrors();
                    },

                    clearErrors() {
                        this.nameError = '';
                        this.statusError = '';
                        this.country_idError = '';
                        this.state_idError = '';
                        this.cityError = '';
                        this.zip_codeError = '';
                        this.address1Error = '';
                    },

                    editItem(item) {
                        this.editIndex = this.items.indexOf(item);
                        this.name = item.name;
                        this.status = item.status;
                        this.country_id = item.country_id;
                        this.state_id = item.state_id;
                        this.city = item.city;
                        this.zip_code = item.zip_code;
                        this.address1 = item.address1;
                        this.clearErrors();

                        $('#country-select').val(this.country_id).trigger('change');
                        setTimeout(() => {
                                            $('#state-select').val(item.state_id);
                                        }, 500);
                        // $('#state-select').val(item.state_id).trigger('change');
                    },

                    get filteredItems() {
                        return this.items.filter(item => {
                            return item.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.status.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.city.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.zip_code.toLowerCase().includes(this.searchTerm.toLowerCase());
                        });
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                 console.log(window.$); // Verify jQuery is available
                if (window.$) {
                    $('#country-select').on('change', function () {
                            var countryId = $(this).val();
                            let url = `/get-states/${countryId}`;

                            ajaxCall(url, 'GET', [[updateStatesDropdown, ['response', 'state-select']]]);
                        });
                          // If editing, set the state select based on existing data
                            const initialStateId = "{{ old('state_id', $location->state_id ?? '') }}"; // Assuming $location is passed to the view
                            console.log(initialStateId);
                            
                            if (initialStateId) {
                                $('#state-select').val(initialStateId);
                            }
                }
               
            });

            
        </script>
    </div>
@endsection

