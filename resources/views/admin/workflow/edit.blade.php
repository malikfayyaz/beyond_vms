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
                    <div class="w-1/2 pr-2">
                        <label class="block mb-2">Client <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            x-model="client" 
                            class="w-full p-2 border rounded h-10 bg-gray-200 text-gray-500"
                            :disabled="true"
                            :placeholder="clientName" 
                        >
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
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="arroval_roleError"></p>
                    </div>
                </div>

                <div class="flex flex-wrap mb-4">
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

                    <div class="w-1/2 mt-2 pr-2">
                        <label for="approval_req" class="block mb-2">Approval Required <span class="text-red-500">*</span></label>
                        <select id="approval_req" x-model="approval_req" class="w-full p-2 border rounded h-10">
                            <option value="" disabled selected>Select Approval Required</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
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
                </div>

                <div class="mb-4">
                    <input
                        type="text"
                        placeholder="Search Client"
                        class="w-full p-2 border rounded"
                        x-model="searchTerm"
                    />
                </div>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Sr #</th>
                            <th class="border p-2">Client</th>
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
                                <td class="border p-2 text-center" x-text="item.client?.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.approvalRole?.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.hiringManager?.first_name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.approval_required ?? 'N/A'"></td>
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
            clientId: "{{ $client_data->id }}", // Store client ID
            clientName: "{{ $client_data->first_name }}", 
            client: "",
            arroval_role: "",
            hiring_manager: "",
            approval_req: "",

            arroval_roleError: "",
            hiring_managerError: "",
            approval_reqError: "",
            items: @json($table_data), // Initialize items with tableData
            
            searchTerm: "",
            editIndex: null,
            error: 0,
            currentUrl: `{{ url()->current() }}`,

            validateFields() {
                this.error = 0; 
                if (this.arroval_role.trim() === "") {
                    this.arroval_roleError = `Please select an Approval Role`;
                    this.error += 1;
                } else {
                    this.arroval_roleError = "";
                }

                if (this.hiring_manager.trim() === "") {
                    this.hiring_managerError = `Please select a Hiring Manager`;
                    this.error += 1;
                } else {
                    this.hiring_managerError = "";
                }

                if (this.approval_req.trim() === "") {
                    this.approval_reqError = `Please select an Approval Status`;
                    this.error += 1;
                } else {
                    this.approval_reqError = "";
                }
            },

            submitData() {
                this.validateFields();
                if (this.error === 0) {
                    let formData = new FormData();  // Prepare the form data
                    let url = '{{ route('admin.workflow.store') }}';
                    
                    // Add your form data (you can add more if needed)
                    formData.append('client_id', this.clientId);
                    formData.append('approval_role_id', this.arroval_role);
                    formData.append('hiring_manager_id', this.hiring_manager);
                    formData.append('approval_required', this.approval_req);
                    ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
                    this.cancelEdit();
                }
            },

            onSuccess(response) {
                // Handle success case
                if (response.success && response.redirect_url) {
                    window.location.href = response.redirect_url; // Redirect to the URL specified in the response
                } 
            },

            cancelEdit() {
                this.client = '';
                this.arroval_role = '';
                this.hiring_manager = '';
                this.approval_req = '';
                this.editIndex = null;
            },

            editItem(item) {
                // Map item data to the form
                this.client = item.client ? item.client.toString() : '';
                this.arroval_role = item.arroval_role ? item.arroval_role.toString() : '';
                this.hiring_manager = item.hiring_manager ? item.hiring_manager.toString() : '';
                this.approval_req = item.approval_req ? item.approval_req.toString() : '';
                this.editIndex = this.items.findIndex(i => i.id === item.id);
            },

            get filteredItems() {
            return this.items.filter(item =>
                (item.client?.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (item.approvalRole?.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (item.hiringManager?.first_name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },
        };
    }
    </script>
@endsection
