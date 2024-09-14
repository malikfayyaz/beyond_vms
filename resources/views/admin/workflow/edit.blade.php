@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="accountManager()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->

            <div class="container mx-auto p-4">
                

                <div class="flex flex-wrap mb-4">
                    <div class="w-1/2 pr-2 ">
                        <label for="hired_man" class="block mb-2">Hired Manager <span class="text-red-500">*</span></label>
                        <input type="text" id="hired_man"  x-model="hired_man" class="w-full p-2 border rounded h-10 bg-gray-200 text-gray-500" :disabled="true" value='{{$bu_data->id}}' placeholder = '{{$bu_data->name}}'>
                    </div>

                    <div class="w-1/2 pr-2">
                        <label for="arroval_role" class="block mb-2">Approval Role <span class="text-red-500">*</span></label>
                        <select
                            id="arroval_role"
                            name="arroval_role"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="arroval_role"
                        >
                            <option value="" disabled>Select Approval Role</option>
                        @foreach ($roles as $record)
                            <option value="{{ $record->id }}">{{ $record->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="arroval_roleError"></p>
                    </div>
                </div>
                <div class="flex flex-wrap mb-4">
                    <!-- Job Family Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="hiring_manager" class="block mb-2">
                            Hiring Manager <span class="text-red-500">*</span>
                        </label>
                        
                        <select
                            id="hiring_manager"
                            name="hiring_manager"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="hiring_manager"
                        >
                            <option value="" disabled>Select Hiring Manager</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }}</option>
                            @endforeach
                        </select>
                        
                        <p class="text-red-500 text-sm mt-1" x-text="hiring_managerError"></p>
                    </div>

                    <!-- Job Family Group Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="approval_req" class="block mb-2">Approval Required <span class="text-red-500">*</span></label>
                        <select id="approval_req" x-model="approval_req" class="w-full p-2 border rounded h-10">
                            <option value="" disabled selected>Select Approval Required</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="approval_reqError"></p>
                    </div>
                </div>

                <div class="flex mb-4">
                    <button
                        @click="submitData()"
                        class="bg-blue-500 text-white px-4 py-2 rounded mr-2"
                    >
                       Create
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
                        placeholder="Search Business Unit"
                        class="w-full p-2 border rounded"
                        x-model="searchTerm"
                    />
                </div>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Sr #</th>
                            <th class="border p-2">Hired Manager</th>
                            <th class="border p-2">Approval Role</th>
                            <th class="border p-2">Hiring Manager</th>
                            <th class="border p-2">Approval Required</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <tr>
                                <td class="border p-2 text-center" x-text="index + 1"></td>
                                <td class="border p-2 text-center" x-text="item.hired_man.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.arroval_role.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.hiring_manager.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.approval_req.name ?? 'N/A'"></td>
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
        </div>
    </div>

    <script>
    function accountManager() {
        return {
            hired_man: "",
            arroval_role: "",
            hiring_manager: "",
            approval_req: "",

            // hired_manError: "",
            arroval_roleError: "",
            hiring_managerError: "",
            approval_reqError: "",
           
            searchTerm: "",
            editIndex: null,
            error: 0,
            currentUrl: `{{ url()->current() }}`,

            validateFields() {
                this.error = 0; 
                console.log(this.arroval_role);
                if (this.arroval_role.trim() === "") {
                    this.arroval_roleError = `Please select a Approval Role`;
                    this.error += 1;
                } else {
                    this.arroval_roleError  = "";
                }
                if (this.hiring_manager.trim() === "") {
                    this.hiring_managerError = `Please select a Hiring Manager`;
                    this.error += 1;
                } else {
                    this.hiring_managerError  = "";
                }
                if (this.approval_req.trim() === "") {
                    this.approval_reqError = `Please select a Approval Status`;
                    this.error += 1;
                } else {
                    this.approval_reqError = "";
                }
            },

            submitData() {
                this.validateFields();
                if (this.error === 0) {
                   
                    let url = this.currentUrl;
                    ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                  
                }
            },

            cancelEdit() {
                this.job_family = '';
                this.job_family_group = '';
                this.editIndex = null;
            },

                editItem(item) {
                    // Ensure these values are treated as strings
                    this.job_family = item.job_family_id ? item.job_family_id.toString() : '';
                    this.job_family_group = item.job_family_group_id ? item.job_family_group_id.toString() : '';
                    this.editIndex = this.items.findIndex(i => i.id === item.id);
                },

            get filteredItems() {
                // return this.items.filter(item =>
                // item.job_family.name.toString().includes(this.searchTerm)
                //     );
                }
            };
        }
    </script>

@endsection
