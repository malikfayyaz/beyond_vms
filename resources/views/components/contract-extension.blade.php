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
                            
                            <!-- Extension Request Content -->
                            <template x-if="selectedUser">
                                <div class="space-y-4">
                            <!-- Top Bar -->
                            <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
                            <h2 class="text-lg font-semibold">Contract Extension Request ({{$contract->contractExtensionRequest->id}})</h2>
                              <button
                                @click="isOpen = false"
                                class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                                </button>
                                </div>
                                    <!-- Extension Details -->
                                    <div class="bg-white border rounded-lg">
                                        <div class="border-b p-4">
                                            <h3 class="text-gray-700 font-medium">Extension Details</h3>
                                        </div>
                                    <div class="divide-y">
                                        <!-- Date -->
                                        <div class="p-4">
                                            <div class="grid grid-cols-2 gap-6">
                                                <div>
                                                    <div class="text-gray-600 mb-2">Start Date</div>
                                                    <div class="line-through text-red-500">{{ formatDate($contract->start_date) }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-600 mb-2">New End Date</div>
                                                    <div class="text-green-500">{{ formatDate($contract->contractExtensionRequest->new_contract_start_date) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="grid grid-cols-2 gap-6">
                                                <div>
                                                    <div class="text-gray-600 mb-2">End Date</div>
                                                    <div class="line-through text-red-500">{{ formatDate($contract->end_date) }}</div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-600 mb-2">New End Date</div>
                                                    <div class="text-green-500">{{ formatDate($contract->contractExtensionRequest->new_contract_end_date) }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rates -->
                                        <div class="p-4">
                                            <div class="grid grid-cols-2 gap-6">
                                                <div>
                                                    <div class="text-gray-600 mb-2">Current Rates</div>
                                                    <div class="space-y-1">
                                                        <div class="text-red-500">Bill Rate: {{ $contract->contractRates->client_bill_rate }}</div>
                                                        <div class="text-red-500">Client Overtime Rate: {{ $contract->contractRates->client_overtime_rate }}</div>
                                                        <div class="text-red-500">Client Doubletime Rate: {{ $contract->contractRates->client_doubletime_rate }}</div>
                                                        <div class="text-red-500">Pay Rate: {{ $contract->contractRates->candidate_pay_rate }}</div>
                                                        <div class="text-red-500">Overtime Pay Rate: {{ $contract->contractRates->candidate_overtime_rate }}</div>
                                                        <div class="text-red-500">Contractor Doubletime Rate: {{ $contract->contractRates->candidate_doubletime_rate }}</div>
                                                        <div class="text-red-500">Budget: {{ $contract->total_estimated_cost }}</div>

                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-600 mb-2">New Rates (Extension)</div>
                                                    <div class="space-y-1">
                                                        <div class="text-green-500">Bill Rate: {{ $contract->contractExtensionRequest->bill_rate }}</div>
                                                        <div class="text-green-500">Overtime Bill Rate: {{ $contract->contractExtensionRequest->overtime_billrate }}</div>
                                                        <div class="text-green-500">Doubletime Bill Rate: {{ $contract->contractExtensionRequest->doubletime_billrate }}</div>
                                                        <div class="text-green-500">Pay Rate: {{ $contract->contractExtensionRequest->pay_rate }}</div>
                                                        <div class="text-green-500">Overtime Pay Rate: {{ $contract->contractExtensionRequest->overtime_payrate }}</div>
                                                        <div class="text-green-500">Contractor Doubletime Rate: {{ $contract->contractExtensionRequest->doubletime_payrate }}</div>
                                                        <div class="text-green-500">Budget: {{ $contract->contractExtensionRequest->new_estimate_cost }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Approval Amount -->
                                    <div class="bg-white border rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <div class="text-gray-600 font-medium">Approval Amount</div>
                                                <div class="text-gray-700">{{ $contract->contractExtensionRequest->new_estimate_cost }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Extension Reason -->
                                    <div class="bg-white border rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <div class="text-gray-600 font-medium">Extension Reason</div>
                                                <div class="text-gray-700">{{getSettingTitleById($contract->contractExtensionRequest->reason_of_extension)}}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="bg-white border rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <div class="text-gray-600 font-medium">Notes</div>
                                                <div class="text-gray-700">{{$contract->contractExtensionRequest->note_of_extension}}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Approval Work Flow Notice -->
                        <!-- Table Data -->
                <div x-data="contractForm" 
                class="p-[30px] rounded border mt-4" 
                :style="{'border-color': 'var(--primary-color)'}">
                    <div class="mb-4 flex items-center gap-2">
                        <i
                            class="fa-regular fa-square-check"
                            :style="{'color': 'var(--primary-color)'}"
                        ></i>
                        <h2
                            class="text-xl font-bold"
                            :style="{'color': 'var(--primary-color)'}"
                        >
                            Contract Extension Workflow
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
                                @if($contract->contractExtensionRequest->contractExtensionWorkflow->isEmpty())
                                    <tr>
                                        <td colspan="9" class="py-4 px-4 text-center text-sm text-gray-600">
                                            No workflows available.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($contract->contractExtensionRequest->contractExtensionWorkflow as $workflow)
                                        <tr>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->hiringManager->full_name }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approver_type}}</td>
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
                                    <label for="contractAttachment" class="block text-sm font-medium text-gray-700 mb-2">Job Attachment</label>
                                    <input
                                        type="file"
                                        id="contractAttachment"
                                        name="contractAttachment"
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
                                </div>
                            </template>

                  </div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contractForm', () => ({
            openModal: false,
            currentRowId: null,
            actionType: '',
            contractId: {{ $contract->id }},
            extId: "{{ $contract->contractExtensionRequest->id }}",
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
                    formData.append('rowId', this.currentRowId);
                    formData.append('contractId', this.contractId);
                    formData.append('extId', this.extId);
                    formData.append('actionType', this.actionType); //for button
                    if (this.actionType === 'Reject') {
                        formData.append('reason', this.reason);
                    }
                    formData.append('note', this.note);
                    const fileInput = document.getElementById('contractAttachment');
                    if (fileInput.files.length > 0) {
                        formData.append('contractAttachment', fileInput.files[0]);
                    }
                    const url = '{{ route('admin.contract.contractExtensionWorkflow') }}';
                    ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                } else {
                    console.log('Form validation failed');
                }
            },

            clearError(field) {
                delete this.errors[field];
            }
        }));
    });
</script>
