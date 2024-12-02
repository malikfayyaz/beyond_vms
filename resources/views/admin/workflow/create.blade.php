@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="accountManager({{ $editIndex ?? 'null' }})">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->

            <div class="container mx-auto p-4">
                <div class="flex flex-wrap mb-4">
                    <div class="w-1/2 pr-2">
                        <label class="block mb-2">Client <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            x-model="formData.clientName" 
                            class="w-full p-2 border rounded h-10 bg-gray-200 text-gray-500"
                            :disabled="true"
                            :placeholder="formData.clientName" 
                        >
                    </div>

                    <div class="w-1/2 pr-2">
                        <label for="arroval_role" class="block mb-2">Approval Role <span class="text-red-500">*</span></label>
                        <select
                            id="arroval_role"
                            name="arroval_role"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="formData.arroval_role"
                        >
                            <option value="" disabled>Select Approval Role</option>

                            @php $user_roles = userRoles(); @endphp
                            @foreach($user_roles as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
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
                            x-model="formData.hiring_manager"
                        >
                            <option value="" disabled>Select Hiring Manager</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                            @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="hiring_managerError"></p>
                    </div>

                    <div class="w-1/2 mt-2 pr-2">
                        <label for="approval_number" class="block mb-2">Approval Number <span class="text-red-500">*</span></label>
                        <select id="approval_number" x-model="formData.approval_number" class="w-full p-2 border rounded h-10">
                            <option value="" disabled selected>Select Approval Number</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>

                            
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="approval_numberError"></p>
                    </div>

                    <div class="w-1/2 mt-2 pr-2">
                        <label for="approval_req" class="block mb-2">Approval Required <span class="text-red-500">*</span></label>
                        <select id="approval_req" x-model="formData.approval_req" class="w-full p-2 border rounded h-10">
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
                       
                        @if(isset($editMode) && $editMode)
                        Update 
                        @else
                            Create 
                        @endif
                    </button>
                </div>
                <div x-data="{ editMode: {{ json_encode($editMode) }} }">
                    <div class="mb-4" x-show="!editMode">
                        <input
                            type="text"
                            placeholder="Search Client"
                            class="w-full p-2 border rounded"
                            x-model="searchTerm"
                        />
                    </div>

                    <table class="w-full border-collapse border" x-show="!editMode">
                        <thead>
                            <tr>
                                <th class="border p-2">Sr #</th>
                                <th class="border p-2">Client</th>
                                <th class="border p-2">Approval Role</th>
                                <th class="border p-2">Hiring Manager</th>
                                <th class="border p-2">Approval Required</th>
                                <th class="border p-2">Approval Number</th>
                                <th class="border p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($table_data as $data)
                            <tr>
                                <td class="border p-2">{{ $loop->index + 1 }}</td>
                                <td class="border p-2">{{ $data->client->full_name ?? 'N/A' }}</td>
                                <td class="border p-2">{{ $data->approvalRole->title ?? 'N/A' }}</td>
                                <td class="border p-2">{{ $data->hiringManager->full_name ?? 'N/A' }}</td>
                                <td class="border p-2">{{ $data->approval_required == "yes" ? "Yes" : "No" ?? 'N/A' }}</td>
                                <td class="border p-2">{{ $data->approval_number ?? 'N/A' }}</td>
                                <td class="border p-2">
                                    <span @click="editItem({{ json_encode($data) }})" class="text-gray-600 cursor-pointer">
                                        <a href="{{route('admin.workflow.edit', $data->id)}}"
                                        class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function accountManager(editIndex) {
        return {
            formData: {
                clientId: "{{ $client_data->id }}", // Store client ID
                clientName: "{{ $client_data->full_name }}", 
                client: '{{ old('client', $workflow->client_id ?? '') }}',
                arroval_role: '{{ old('arroval_role', $workflow->approval_role_id ?? '') }}',
                hiring_manager: '{{ old('hiring_manager', $workflow->hiring_manager_id ?? '') }}',
                approval_req: '{{ old('approval_req', $workflow->approval_required ?? '') }}',
                approval_number: '{{ old('approval_number', $workflow->approval_number ?? '') }}',
            },
            // clientId: "{{ $client_data->id }}", // Store client ID
            // clientName: "{{ $client_data->full_name }}", 
            // client: "",
            // arroval_role: "",
            // hiring_manager: "",
            // approval_req: "",

            approval_numberError:"",
            arroval_roleError: "",
            hiring_managerError: "",
            approval_reqError: "",
            // items: @json($table_data), // Initialize items with tableData
            editIndex: editIndex,

            searchTerm: "",
            // editIndex: null,
            error: 0,
            currentUrl: `{{ url()->current() }}`,

            validateFields() {
                this.error = 0; 
                if (this.formData.arroval_role.trim() === "") {
                    this.arroval_roleError = `Please select an Approval Role`;
                    this.error += 1;
                } else {
                    this.arroval_roleError = "";
                }

                if (this.formData.hiring_manager.trim() === "") {
                    this.hiring_managerError = `Please select a Hiring Manager`;
                    this.error += 1;
                } else {
                    this.hiring_managerError = "";
                }

                if (this.formData.approval_req.trim() === "") {
                    this.approval_reqError = `Please select an Approval Status`;
                    this.error += 1;
                } else {
                    this.approval_reqError = "";
                }
                //alert(this.approval_number);
                if (this.formData.approval_number.trim() === "") {
                    this.approval_numberError = `Please select an Approval Number`;
                    this.error += 1;
                } else {
                    this.approval_numberError = "";
                }
            },

            submitData() {
                this.validateFields();
                if (this.error === 0) {
                    let formData = new FormData();  // Prepare the form data
                    // Add your form data (you can add more if needed)
                    formData.append('client_id', this.formData.clientId);
                    formData.append('approval_role_id', this.formData.arroval_role);
                    formData.append('hiring_manager_id', this.formData.hiring_manager);
                    formData.append('approval_required', this.formData.approval_req);
                    formData.append('approval_number', this.formData.approval_number);
                    // console.log(this.formData.approval_req);
                    let url = '{{ route('admin.workflow.store') }}';
                    if (this.editIndex !== null) {
                        
                        url = '{{ route("admin.workflow.update", ":id") }}'; // Update URL
                        url = url.replace(':id', editIndex); // Replace placeholder with actual ID
                        formData.append('_method', 'PUT'); // Laravel expects PUT method for updates
                    };
                    // console.log(url);
                    ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
                    // this.cancelEdit();
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
                this.approval_number = '';
                this.editIndex = null;
            },

            editItem(item) {
                // Map item data to the form
                this.client = item.client ? item.client.toString() : '';
                this.arroval_role = item.arroval_role ? item.arroval_role.toString() : '';
                this.hiring_manager = item.hiring_manager ? item.hiring_manager.toString() : '';
                this.approval_req = item.approval_req ? item.approval_req.toString() : '';
                this.approval_number=item.approval_number ? item.approval_number.toString() : '';
                this.editIndex = this.items.findIndex(i => i.id === item.id);
            },

            get filteredItems() {
            return this.items.filter(item =>
                (item.client.first_name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (item.approval_role.name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (item.hiring_manager.first_name ?? '').toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },
        };
    }
    </script>
@endsection
