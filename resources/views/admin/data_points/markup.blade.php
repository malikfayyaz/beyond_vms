@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="locationManager()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->
            
            <h1>Markup</h1>
            
            <div class="flex mb-4">
               <div class="w-1/2 pr-2">
                    <label for="location-select" class="block mb-2">Location <span class="text-red-500">*</span></label>
                    <select id="location-select" name="location_id" x-model="location_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Location </option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="location_Error" class="text-red-500 text-sm mt-1" x-text="location_Error"></p>
                </div>

                 <div class="w-1/2 pr-2">
                    <label for="vendor-select" class="block mb-2">Vendor <span class="text-red-500">*</span></label>
                    <select id="vendor-select" name="vendor_id" x-model="vendor_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Vendor </option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->first_name }} {{ $vendor->last_name }}</option>
                        @endforeach
                    </select>
                    <p x-show="vendor_Error" class="text-red-500 text-sm mt-1" x-text="vendor_Error"></p>
                </div>
            </div>
            
            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="category-select" class="block mb-2">Category <span class="text-red-500">*</span></label>
                    <select id="category-select" name="category_id" x-model="category_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->job_title }}</option>
                        @endforeach
                    </select>
                    <p x-show="category_Error" class="text-red-500 text-sm mt-1" x-text="category_Error"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="state-select" class="block mb-2">Markup <span class="text-red-500">*</span></label>
                    <input type="text" name="markup_value" id="markup_value" x-model="markup_value" class="w-full p-2 border rounded h-10">
                    <p x-show="markup_Error" class="text-red-500 text-sm mt-1" x-text="markup_Error"></p>
                </div>
            </div>

            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="status-select" class="block mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status-select" name="status" x-model="status" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <p x-show="status_Error" class="text-red-500 text-sm mt-1" x-text="status_Error"></p>
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
                        <th class="border p-2">Vendor</th>
                        <th class="border p-2">Location</th>
                        <th class="border p-2">Category</th>
                        <th class="border p-2">Markup</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in filteredItems" :key="index">
                        <tr>
                            <td class="border p-2 text-center" x-text="index + 1"></td>
                            <td class="border p-2 text-center" x-text="item.vendor.first_name"></td>
                            <td class="border p-2 text-center" x-text="item.location.name"></td>
                            <td class="border p-2 text-center" x-text="item.category.job_title ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.markup_value ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.status ?? 'N/A'"></td>
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
                    markup_value: "",
                    category_id: "",
                    vendor_id: "",
                    location_id: "",
                    status: "",

                    status_Error: "",
                    markup_Error: "",
                    category_Error: "",
                    vendor_Error: "",
                    location_Error: "",
                    
                    items: @json($data),
                    searchTerm: "",
                    editIndex: null,
                    error: 0,
                    currentUrl: `{{ url()->current() }}`,

                    validateFields() {
                        this.error = 0;
                        if (this.markup_value.trim() === "") {
                            this.markup_Error = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.markup_Error = "";
                        }

                        if (this.category_id === "") {
                            this.category_Error = "Please fill this field";
                            this.error += 1;
                        } else {
                            this.category_Error = "";
                        }

                        if (this.vendor_id === "") {
                            this.vendor_Error = "Please select a country";
                            this.error += 1;
                        } else {
                            this.vendor_Error = "";
                        }

                        if (this.location_id === "") {
                            this.location_Error = "Please select a state";
                            this.error += 1;
                        } else {
                            this.location_Error = "";
                        }

                        if (this.status.trim() === "") {
                            this.status_Error = "Please fill this field";
                            this.status += 1;
                        } else {
                            this.status_Error = "";
                        }

                    },

                    submitData() {
                        this.validateFields();

                        if (this.error == 0) {
                            const formData = new FormData();
                            formData.append('markup_value', this.markup_value);
                            formData.append('status', this.status);
                            formData.append('location_id', this.location_id);
                            formData.append('vendor_id', this.vendor_id);
                            formData.append('category_id', this.category_id);
                            if (this.editIndex !== null) {
                                formData.append('id', this.items[this.editIndex].id);
                            }
                            
                            let url = this.currentUrl;
                            ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                            this.cancelEdit();
                        }
                    },

                    cancelEdit() {
                        this.markup_value = '';
                        this.status = '';
                        this.location_id = '';
                        this.vendor_id = '';
                        this.category_id = '';
                        this.editIndex = null;
                        this.clearErrors();
                    },

                    clearErrors() {
                        this.status_Error = '';
                        this.location_Error = '';
                        this.vendor_Error = '';
                        this.category_Error = '';
                        this.category_Error = '';
                    },

                    editItem(item) {
                        this.editIndex = this.items.indexOf(item);
                        this.vendor_id = item.vendor_id;
                        this.status = item.status;
                        this.location_id = item.location_id;
                        this.category_id = item.category_id;
                        this.markup_value = item.markup_value;
                        this.clearErrors();
                        // $('#state-select').val(item.state_id).trigger('change');
                    },

                    get filteredItems() {
                        return this.items.filter(item => {
                            // console.log(item.status);
                            return item.vendor.first_name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.category.job_title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.location.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                   item.markup_value.toLowerCase().includes(this.searchTerm.toLowerCase())||
                                   item.status.toLowerCase().includes(this.searchTerm.toLowerCase());
                        });
                    }
                }
            }
            
        </script>
    </div>
@endsection

