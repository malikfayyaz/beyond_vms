@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="divisionManager()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->

            <div class="container mx-auto p-4">
                <h1>Division Branch Zone Management</h1>
                <div class="flex flex-wrap mb-4">
                    <!-- Division Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="division" class="block mb-2">Division <span class="text-red-500">*</span></label>
                        <select
                            id="division"
                            name="division"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="division"
                        >
                            <option value="" disabled>Select Division</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="divisionError"></p>
                    </div>

                    <!-- Branch Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="branch" class="block mb-2">Branch <span class="text-red-500">*</span></label>
                        <select
                            id="branch"
                            name="branch"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="branch"
                        >
                            <option value="" disabled>Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="branchError"></p>
                    </div>

                    <!-- Zone Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="zone" class="block mb-2">Zone <span class="text-red-500">*</span></label>
                        <select
                            id="zone"
                            name="zone"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="zone"
                        >
                            <option value="" disabled>Select Zone</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="zoneError"></p>
                    </div>

                    <!-- BU Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="bu" class="block mb-2">Business Unit <span class="text-red-500">*</span></label>
                        <select
                            id="bu"
                            name="bu"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="bu"
                        >
                            <option value="" disabled>Select Business Unit</option>
                        @foreach ($bus as $bu)
                            <option value="{{ $bu->id }}">{{ $bu->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="buError"></p>
                    </div>

                    <!-- Status Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="status" class="block mb-2">Status <span class="text-red-500">*</span></label>
                        <select
                            id="status"
                            name="status"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="status"
                        >
                            <option value="" disabled>Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="statusError"></p>
                    </div>
                </div>

                <div class="flex mb-4">
                    <button
                        @click="submitData()"
                        class="bg-blue-500 text-white px-4 py-2 rounded mr-2"
                    >
                        <span x-text="editIndex !== null ? 'Update' : 'Add'"></span>
                    </button>
                    <button
                        @click="cancelEdit()"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                </div>

                <div class="mb-4">
                    <input
                        type="text"
                        placeholder="Search Division"
                        class="w-full p-2 border rounded"
                        x-model="searchTerm"
                    />
                </div>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Sr #</th>
                            <th class="border p-2">Division</th>
                            <th class="border p-2">Branch</th>
                            <th class="border p-2">Zone</th>
                            <th class="border p-2">Business Unit</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through items (Assuming items are passed from the controller) -->
                        @foreach ($data as $index => $item)
                            <tr>
                                <td class="border p-2 text-center">{{ $index + 1 }}</td>
                                <td class="border p-2 text-center">{{ $item->division }}</td>
                                <td class="border p-2 text-center">{{ $item->branch }}</td>
                                <td class="border p-2 text-center">{{ $item->zone }}</td>
                                <td class="border p-2 text-center">{{ $item->bu }}</td>
                                <td class="border p-2 text-center">{{ $item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td class="border p-2 text-center">
                                    <a href="#" @click.prevent="editItem({{ json_encode($item) }})" class="text-gray-600 cursor-pointer">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function divisionManager() {
        return {
            division: "",
            branch: "",
            zone: "",
            bu: "",
            status: "",
            divisionError: "",
            branchError: "",
            zoneError: "",
            buError: "",
            statusError: "",
            items: @json($data),
            searchTerm: "",
            editIndex: null,
            error: 0,
            currentUrl: `{{ url()->current() }}`,

            validateFields() {
                this.error = 0; 
                if (this.division.trim() === "") {
                    this.divisionError = `Please select a Division`;
                    this.error += 1;
                } else {
                    this.divisionError = "";
                }
                if (this.branch.trim() === "") {
                    this.branchError = `Please select a Branch`;
                    this.error += 1;
                } else {
                    this.branchError = "";
                }
                if (this.zone.trim() === "") {
                    this.zoneError = `Please select a Zone`;
                    this.error += 1;
                } else {
                    this.zoneError = "";
                }
                if (this.bu.trim() === "") {
                    this.buError = `Please select a Business Unit`;
                    this.error += 1;
                } else {
                    this.buError = "";
                }
                if (this.status.trim() === "") {
                    this.statusError = `Please select a Status`;
                    this.error += 1;
                } else {
                    this.statusError = "";
                }
            },

            submitData() {
                this.validateFields();
                if (this.error === 0) {
                    const formData = new FormData();
                    formData.append('division', this.division);
                    formData.append('branch', this.branch);
                    formData.append('zone', this.zone);
                    formData.append('bu', this.bu);
                    formData.append('status', this.status);
                    if (this.editIndex !== null) {
                        formData.append('id', this.items[this.editIndex].id);
                    }

                    fetch(this.currentUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.text()) // Change to response.text() to handle non-JSON responses
                    .then(text => {
                        try {
                            const data = JSON.parse(text); // Attempt to parse JSON
                            if (data.success) {
                                window.location.href = data.redirect_url;
                            } else {
                                console.error('Validation errors:', data.errors);
                                this.divisionError = data.errors.division || '';
                                this.branchError = data.errors.branch || '';
                                this.zoneError = data.errors.zone || '';
                                this.buError = data.errors.bu || '';
                                this.statusError = data.errors.status || '';
                            }
                        } catch (e) {
                            console.error('Response is not valid JSON:', text);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            },

            cancelEdit() {
                this.division = '';
                this.branch = '';
                this.zone = '';
                this.bu = '';
                this.status = '';
                this.editIndex = null;
            },

            editItem(item) {
                this.division = item.division;
                this.branch = item.branch;
                this.zone = item.zone;
                this.bu = item.bu;
                this.status = item.status;
                this.editIndex = this.items.findIndex(i => i.id === item.id);
            },

            get filteredItems() {
                return this.items.filter(item =>
                    item.division.toString().includes(this.searchTerm)
                );
            }
        }
    }
    </script>

@endsection
