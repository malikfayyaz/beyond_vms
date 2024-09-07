@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="divisionBranchZoneManager()">
            @include('admin.layouts.partials.alerts') <!-- Include alerts -->

            <h1>Division Branch Zone Configurations</h1>

            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="division-select" class="block mb-2">Division <span class="text-red-500">*</span></label>
                    <select id="division-select" name="division_id" x-model="division_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Division</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="division_idError" class="text-red-500 text-sm mt-1" x-text="division_idError"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="branch-select" class="block mb-2">Branch <span class="text-red-500">*</span></label>
                    <select id="branch-select" name="branch_id" x-model="branch_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="branch_idError" class="text-red-500 text-sm mt-1" x-text="branch_idError"></p>
                </div>
            </div>

            <div class="flex mb-4">
                <div class="w-1/2 pr-2">
                    <label for="zone-select" class="block mb-2">Zone <span class="text-red-500">*</span></label>
                    <select id="zone-select" name="zone_id" x-model="zone_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Zone</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="zone_idError" class="text-red-500 text-sm mt-1" x-text="zone_idError"></p>
                </div>
                <div class="w-1/2 pl-2">
                    <label for="bu-select" class="block mb-2">Business Unit <span class="text-red-500">*</span></label>
                    <select id="bu-select" name="bu_id" x-model="bu_id" class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select Business Unit</option>
                        @foreach ($businessUnits as $bu)
                            <option value="{{ $bu->id }}">{{ $bu->name }}</option>
                        @endforeach
                    </select>
                    <p x-show="bu_idError" class="text-red-500 text-sm mt-1" x-text="bu_idError"></p>
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
                <input type="text" x-model="searchTerm" placeholder="Search Config" class="w-full p-2 border rounded">
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
                    <template x-for="(item, index) in filteredItems" :key="index">
                        <tr>
                            <td class="border p-2 text-center" x-text="index + 1"></td>
                            <td class="border p-2 text-center" x-text="item.division.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.branch.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.zone.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.bu.name ?? 'N/A'"></td>
                            <td class="border p-2 text-center" x-text="item.status"></td>
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
            function divisionBranchZoneManager() {
                return {
                    division_id: "",
                    branch_id: "",
                    zone_id: "",
                    bu_id: "",
                    status: "",
                    division_idError: "",
                    branch_idError: "",
                    zone_idError: "",
                    bu_idError: "",
                    statusError: "",
                    items: @json($data),
                    searchTerm: "",
                    editIndex: null,
                    error: 0,
                    currentUrl: `{{ url()->current() }}`,

                    validateFields() {
                        this.error = 0;
                        if (this.division_id === "") {
                            this.division_idError = "Please select a division";
                            this.error += 1;
                        } else {
                            this.division_idError = "";
                        }

                        if (this.branch_id === "") {
                            this.branch_idError = "Please select a branch";
                            this.error += 1;
                        } else {
                            this.branch_idError = "";
                        }

                        if (this.zone_id === "") {
                            this.zone_idError = "Please select a zone";
                            this.error += 1;
                        } else {
                            this.zone_idError = "";
                        }

                        if (this.bu_id === "") {
                            this.bu_idError = "Please select a business unit";
                            this.error += 1;
                        } else {
                            this.bu_idError = "";
                        }

                        if (this.status === "") {
                            this.statusError = "Please select a status";
                            this.error += 1;
                        } else {
                            this.statusError = "";
                        }
                    },

                    submitData() {
                        this.validateFields();

                        if (this.error == 0) {
                            const formData = new FormData();
                            formData.append('division_id', this.division_id);
                            formData.append('branch_id', this.branch_id);
                            formData.append('zone_id', this.zone_id);
                            formData.append('bu_id', this.bu_id);
                            formData.append('status', this.status);
                            if (this.editIndex !== null) {
                                formData.append('id', this.items[this.editIndex].id);
                            }
                            
                            let url = this.currentUrl;
                            ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                            this.cancelEdit();
                        }
                    },

                    cancelEdit() {
                        this.division_id = '';
                        this.branch_id = '';
                        this.zone_id = '';
                        this.bu_id = '';
                        this.status = '';
                        this.editIndex = null;
                        this.clearErrors();
                    },

                    clearErrors() {
                        this.division_idError = '';
                        this.branch_idError = '';
                        this.zone_idError = '';
                        this.bu_idError = '';
                        this.statusError = '';
                    },

                    editItem(item) {
                        this.division_id = item.division.id;
                        this.branch_id = item.branch.id;
                        this.zone_id = item.zone.id;
                        this.bu_id = item.bu.id;
                        this.status = item.status;
                        this.editIndex = this.items.indexOf(item);
                    },

                    get filteredItems() {
                        if (this.searchTerm === "") {
                            return this.items;
                        }
                        return this.items.filter((item) => {
                            return (
                                item.division.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                item.branch.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                item.zone.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                item.bu.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                            );
                        });
                    }
                }
            }
        </script>
    </div>
@endsection
