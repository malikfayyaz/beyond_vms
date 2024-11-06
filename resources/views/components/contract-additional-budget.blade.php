<div
                              x-show="isOpen"
                                  x-transition:enter="transition ease-out duration-300"
                                  x-transition:enter-start="transform translate-x-full"
                                  x-transition:enter-end="transform translate-x-0"
                                  x-transition:leave="transition ease-in duration-300"
                                  x-transition:leave-start="transform translate-x-0"
                                  x-transition:leave-end="transform translate-x-full"
                                  @click.outside="isOpen = false"
                                  class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24"
                      >
                      
                    <template x-if="selectedUser">
                      <div>
                        <!-- Top Bar -->
                        <div
                          class="flex justify-between items-center p-4 bg-gray-800 text-white"
                        >
                        
                        <h2 class="text-lg font-semibold">Additional Budget Request (<span  >{{$contract->latestPendingBudgetRequest->id}}</span>)</h2>
                          <button
                            @click="isOpen = false"
                            class="text-gray-500 hover:text-gray-700"
                          >
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                        
                        <!-- Additional budget request details -->
                        <div class="p-4">
                            <table class="border w-full">
                                <thead>
                                <tr class="text-left border-b">
                                    <th class=" p-2">
                                    Additional Budget Request Details</th>
                                </tr>
                            </thead>
                                <tbody>
                                <tr>
                                    <td class="p-2">Request Amount  RT :</td>
                                    <td class="p-2 text-green-800 font-bold">${{
                                    $contract->latestPendingBudgetRequest->amount }}</td>
                                </tr>
                            </tbody>
                            </table>
                         </div>
                         
                         <!-- Reason for Additional Budget Request & Notes -->
                    <div class="bg-gray-100 p-4 pt-0 rounded-lg">
                        <h3 class="text-md font-semibold">Reason for Additional Budget Request</h3>
                        <div class="ml-4 mt-2 text-gray-600">
                            {{ getSettingTitleById($contract->latestPendingBudgetRequest->additional_budget_reason) }}
                        </div>
                    </div>
                    <div class="bg-gray-100 p-4 pt-0 rounded-lg">
                        <h3 class="text-md font-semibold">Additional Budget Request Notes</h3>
                        <div class="ml-4 mt-2 text-gray-600">
                        {{ $contract->latestPendingBudgetRequest->notes }}
                        </div>
                    </div>
                        <!-- Table Data -->
                                        <div x-data="{
    openModal: false,
    currentRowId: null,
        actionType: '',
        contractId: {{ $contract->id }},
    reason: '',
    note: '',
    errors: {},
    validateForm() {
        this.errors = {};
       if (this.actionType === 'Reject' && !this.reason) {
            this.errors.reason = 'Please select a reason';
        }
        if (!this.note.trim()) this.errors.note = 'Please enter a note';
        return Object.keys(this.errors).length === 0;
    },

    submitForm() {
    console.log('Form submitted successfully');
    const isValid = this.validateForm();
    if (isValid) {
        // Create FormData object
        const formData = new FormData();
        formData.append('rowId', this.currentRowId); // Use currentRowId for identification
        formData.append('contractId', this.contractId);
        formData.append('actionType', this.actionType); //for button
        if (this.actionType === 'Reject') {
                formData.append('reason', this.reason);
            }
        formData.append('note', this.note);
        const fileInput = document.getElementById('jobAttachment');
        if (fileInput.files.length > 0) {
            formData.append('jobAttachment', fileInput.files[0]);
        }
        // Call the AJAX function
                const url = '{{ route('admin.contract.contractBudgetWorkflow') }}';
                ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);
    } else {
        console.log('Form validation failed');
    }
},




    clearError(field) {
        delete this.errors[field];
    }
}"
                     class="p-[30px] rounded border mt-4"
                     :style="{'border-color': 'var(--primary-color)'}"
                >
                    <div class="mb-4 flex items-center gap-2">
                        <i
                            class="fa-regular fa-square-check"
                            :style="{'color': 'var(--primary-color)'}"
                        ></i>
                        <h2
                            class="text-xl font-bold"
                            :style="{'color': 'var(--primary-color)'}"
                        >
                            Contract Workflow
                        </h2>
                    </div>
                    <div class="bg-white shadow rounded-lg">
                        <div class="overflow-hidden">
                            <table class="w-full">
                                <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approver Name</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approved/Rejected By</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approved/Rejected Date & Time</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Action</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @if($contract->ContractBudgetWorkflow->isEmpty())
                                    <tr>
                                        <td colspan="9" class="py-4 px-4 text-center text-sm text-gray-600">
                                            No workflows available.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($contract->ContractBudgetWorkflow as $workflow)
                                        <tr>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->hiringManager->full_name }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approver_type }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approved_datetime ?? 'N/A' }}</td>
                                            <td class="py-4 px-4 text-center text-sm">
                                                   <div class="flex space-x-2">
        @if ($workflow->status == 'Approved')
            <span class="block w-full bg-green-500 text-white py-2 px-4 rounded text-center font-bold">
                {{$workflow->status}}
            </span>
        @elseif ($workflow->status == 'Rejected')
            <span class="block w-full bg-red-500 text-white py-2 px-4 rounded text-center font-bold">
                {{$workflow->status}}
            </span>
        @else
            <button
                @click="actionType = 'Accept'; openModal = true; currentRowId = {{ $workflow->id }}; submitForm(currentRowId, actionType);"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded flex items-center"
            >
                <i class="fas fa-check-circle fa-2x mr-2"></i>
            </button>

            <button
                @click="actionType = 'Reject'; openModal = true; currentRowId = {{ $workflow->id }}; submitForm(currentRowId, actionType);"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded flex items-center"
            >
                <i class="fas fa-times-circle fa-2x mr-2"></i>
            </button>
        @endif
</div>

                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div
                        x-show="openModal"
                        @click.away="openModal = false"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    >
                        <div
                            class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white"
                            @click.stop
                        >
                            <!-- Header -->
                            <div class="flex items-center justify-between border-b p-4">
                                <h2 class="text-xl font-semibold"><!--Reject--> Accept Candidate</h2>
                                <button
                                    @click="openModal = false"
                                    class="text-gray-400 hover:text-gray-600 bg-transparent hover:bg-transparent"
                                >
                                    &times;
                                </button>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                        <form @submit.prevent="submitForm" id="generalformwizard">
                                <div class="mb-4">
                                    <div x-show="actionType === 'Reject'" class="mt-4">
                                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                                            Reason for Rejection
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select id="reason" x-model="reason" @input="clearError('reason')" class="w-full">
                                            <option value="">Select</option>
                                            
                                            @foreach (checksetting(29) as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option> <!-- Use index as value -->
                                            @endforeach
                                        </select>
                                        <p x-show="errors.reason" class="text-red-500 text-sm mt-1" x-text="errors.reason"></p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                                        Note <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="note"
                                        x-model="note"
                                        @input="clearError('note')"
                                        rows="4"
                                        class="w-full border border-gray-300 rounded-md shadow-sm"
                                    ></textarea>
                                    <p x-show="errors.note" class="text-red-500 text-sm mt-1" x-text="errors.note"></p>
                                </div>
                                <div class="mb-4">
                                    <label for="jobAttachment" class="block text-sm font-medium text-gray-700 mb-2">Job Attachment</label>
                                    <input
                                        type="file"
                                        id="jobAttachment"
                                        name="jobAttachment"
                                        class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    />
                                </div>
                            </form>
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end space-x-2 border-t p-4">
                                <button
                                    type="button"
                                    @click="openModal = false"
                                    class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                                >
                                    Close
                                </button>
                                <button
                                    type="button"
                                    @click="submitForm"
                                    class="rounded-md bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                        
                        <!-- Modal -->
                        <div
                            x-show="showModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            @click.self="showModal = false"
                        >
                            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                                <div class="flex justify-between items-center mb-4">
                                     <div>
            <h3 class="text-lg font-semibold" x-text="modalType === 'approve' ? 'Approve Request' : 'Reject Request'"></h3>
            <p class="text-sm text-gray-600">Request ID: <span x-text="selectedUser?.id || ''"></span></p>
        </div>
                                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Comments
                                    </label>
                                    <textarea 
                                        x-model="comment"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        rows="4"
                                    ></textarea>
                                </div>
                                
                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Attachment
                                    </label>
                                    <input 
                                        type="file" 
                                        class="w-full px-3 py-2 border rounded text-gray-700 focus:outline-none focus:shadow-outline"
                                    >
                                </div>
                                
                                <div class="flex justify-end gap-2">
                                    <button 
                                        @click="showModal = false"
                                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                                    >
                                        Close
                                    </button>
                                    <button 
                                        @click="
                                            if (selectedUser) {
                                                selectedUser.status = modalType === 'approve' ? 'approved' : 'rejected';
                                                selectedUser.approvedBy = 'Current User';
                                                selectedUser.dateTime = new Date().toLocaleString();
                                                showModal = false;
                                                comment = '';
                                            }
                                        "
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                        :class="{ 'bg-green-500 hover:bg-green-600': modalType === 'approve', 'bg-red-500 hover:bg-red-600': modalType === 'reject' }"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </template>
                  </div>