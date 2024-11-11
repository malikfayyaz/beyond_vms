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
                            <!-- Rate Change Content -->
                            <template x-if="selectedUser">
                                <div class="space-y-4">
                                <!-- Top Bar -->
                            <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
                            <h2 class="text-lg font-semibold">Contract Extension Request ()</h2>
                              <button
                                @click="isOpen = false"
                                class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                                </button>
                                </div>
                                    <!-- Assignment Information -->
                                    <div class="bg-white border rounded-lg">
                                        <div class="border-b p-4">
                                            <h3 class="text-gray-700 font-medium">Assignment Information</h3>
                                        </div>

                                        <!-- Rates Section -->
                                        <div class="divide-y">
                                            <div class="p-4">
                                                <div class="text-gray-600 mb-3">RATES</div>
                                                <div class="grid grid-cols-2 gap-6">
                                                    <div class="space-y-2">
                                                        <div class="line-through text-red-500">RT: $87.50</div>
                                                        <div class="line-through text-red-500">OT: $86.25</div>
                                                        <div class="line-through text-red-500">P-RT: $80.00</div>
                                                        <div class="line-through text-red-500">P-OT: $75.00</div>
                                                        <div class="text-gray-500">Markup: 0.00</div>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <div class="text-green-500">RT: $30.00</div>
                                                        <div class="text-green-500">OT: $45.00</div>
                                                        <div class="text-green-500">P-RT: $30.00</div>
                                                        <div class="text-green-500">P-OT: $45.00</div>
                                                        <div class="text-gray-500">Markup: 0.00</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Budget -->
                                            <div class="p-4">
                                                <div class="text-gray-600 mb-2">BUDGET</div>
                                                <div class="grid grid-cols-2 gap-6">
                                                    <div class="line-through text-red-500">$108,500.00</div>
                                                    <div class="text-green-500">$73,200.00</div>
                                                </div>
                                            </div>

                                            <!-- Dates -->
                                            <div class="p-4">
                                                <div class="grid grid-cols-[120px,1fr,1fr] gap-4">
                                                    <div class="text-gray-600">START DATE</div>
                                                    <div class="text-gray-700">09/18/2023</div>
                                                    <div class="text-gray-700">09/18/2023</div>
                                                </div>
                                            </div>

                                            <div class="p-4">
                                                <div class="grid grid-cols-[120px,1fr,1fr] gap-4">
                                                    <div class="text-gray-600">END DATE</div>
                                                    <div class="text-gray-700">11/17/2024</div>
                                                    <div class="text-gray-700">11/17/2024</div>
                                                </div>
                                            </div>

                                            <div class="p-4">
                                                <div class="grid grid-cols-[120px,1fr] gap-4">
                                                    <div class="text-gray-600">REQUESTED DATE</div>
                                                    <div class="text-gray-700">07/24/2024</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Effective Date -->
                                    <div class="bg-white border rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <div>
                                                <div class="text-gray-600 font-medium">Effective Date</div>
                                                <div class="text-gray-700">07/24/2024</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rejection Notice -->
                                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                                        <div class="text-red-700">Reason of Rejection: Submitted in Error</div>
                                        <div class="text-red-600 mt-1">Rejected By: Rachel Coy (Icon Information
                                            Consultants) on 07/26/2024 12:25 PM</div>
                                    </div>

                                    <!-- Approvers Table -->
                                    <div class="bg-white border rounded-lg">
                                        <table class="w-full">
                                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                                <tr>
                                                    <th class="p-4 text-left">Approver Name</th>
                                                    <th class="p-4 text-left">Approved/Rejected By</th>
                                                    <th class="p-4 text-left">Approval Notes</th>
                                                    <th class="p-4 text-left">Approval Document</th>
                                                    <th class="p-4 text-left">Date & Time</th>
                                                    <th class="p-4 text-left">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="p-4">
                                                        <div class="flex items-center gap-2">
                                                            <div
                                                                class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                                                R
                                                            </div>
                                                            <span class="text-gray-700">Rachel Coy (Icon Information
                                                                Consultants)</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-4 text-gray-700">Rachel Coy</td>
                                                    <td class="p-4"></td>
                                                    <td class="p-4"></td>
                                                    <td class="p-4 text-gray-700">07/26/2024 12:25 PM</td>
                                                    <td class="p-4">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Rejected
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Impacted Timesheets -->
                                    <div class="bg-white border rounded-lg">
                                        <div class="p-4 border-b">
                                            <h3 class="text-gray-700 font-medium">Impacted Timesheets List</h3>
                                        </div>
                                        <div class="p-4">
                                            <table class="w-full text-sm">
                                                <thead class="text-xs uppercase text-gray-500">
                                                    <tr>
                                                        <th class="p-2 text-left">Sr.No</th>
                                                        <th class="p-2 text-left">Status</th>
                                                        <th class="p-2 text-left">Timesheet ID</th>
                                                        <th class="p-2 text-left">Contractor Name</th>
                                                        <th class="p-2 text-left">Hours</th>
                                                        <th class="p-2 text-left">Billable Amount</th>
                                                        <th class="p-2 text-left">Duration</th>
                                                        <th class="p-2 text-left">Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="p-2 text-center" colspan="8">No data found</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
                    // Call the AJAX function
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
