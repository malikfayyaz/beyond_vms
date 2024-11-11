<div class="">
    <div class="mx-4 rounded p-8">
        <div class="mx-auto relative" x-data="timeline">
            <!-- Main Timeline -->
            <div class="relative">
                <!-- Vertical line -->
                <div class="absolute left-4 top-4 bottom-4 w-[1px] bg-gray-300"></div>

                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-4 mb-8 relative group">
                        <!-- Timeline dot -->
                        <div class="flex-shrink-0 relative z-10">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="text-sm text-gray-500 mb-1" x-text="formatDate(item.date)"></div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="font-medium text-gray-900" x-text="item.title"></div>
                                <button @click="openSidebar(item)"
                                    class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded hidden group-hover:inline-block">
                                    View
                                </button>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span x-text="item.description"></span>
                                <span x-text="item.author"></span>
                                <span x-text="item.id"></span>.
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Overlay -->
            <template x-if="isOpen">
                <div class="fixed inset-0 bg-black bg-opacity-30 z-40" @click="closeSidebar()">
                </div>
            </template>

            <!-- Sidebar -->
            <div x-show="isOpen" @click.outside="closeSidebar"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 w-[650px] bg-white shadow-xl transform z-50 flex flex-col">

                <!-- Sidebar Header - Fixed -->
                <div class="flex items-center justify-between p-4 border-b bg-white">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <span x-text="selectedItem?.author?.charAt(0)"></span>
                        </div>
                        <div>
                            <h3 class="font-medium" x-text="getSidebarTitle(selectedItem)"></h3>
                            <p class="text-sm text-gray-500" x-text="selectedItem?.author"></p>
                        </div>
                    </div>
                    <button @click="closeSidebar" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Content -->
                <div class="flex-1 overflow-y-auto p-4">
                    <!-- Additional Budget Content -->
                    <template x-if="selectedItem?.title === 'Additional Budget'">
                        <div class="space-y-4">
                            <!-- Details Section -->
                            <div class="bg-white border rounded-lg">
                                <div class="border-b p-4">
                                    <h3 class="text-gray-700 font-medium">Additional Budget Request Details</h3>
                                </div>

                                <div class="p-4">
                                    <div class="grid grid-cols-[120px,1fr] gap-4 items-center">
                                        <div class="text-gray-600">Request Amount</div>
                                        <div class="text-green-500 font-medium">RT: $25,196.00</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reason Section -->
                            <div class="bg-white border rounded-lg p-4">
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600 font-medium mb-1">Reason for Additional Budget
                                            Request</div>
                                        <div class="text-gray-700">Update to Spend</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div class="bg-white border rounded-lg p-4">
                                <div class="flex items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0h8v12H6V4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <div class="text-gray-600 font-medium mb-1">Notes</div>
                                        <div class="text-gray-700">Additional budget to cover the past three
                                            weeks for back pay plus to pay the worker until the end date of
                                            11/17/24</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Notice -->
                            <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                                <div class="text-red-700 font-medium mb-1">Reason of Rejection: Revision
                                    Cancelled</div>
                                <div class="text-red-600">Notes: Budget was underestimated. Need $36,000</div>
                                <div class="text-red-600 mt-1">Rejected By: Paul DeSisto On 08/13/2024 02:16 PM
                                </div>
                            </div>

                            <!-- Approval Table -->
                            <div class="bg-white border rounded-lg">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider p-4">
                                                Approver Name
                                            </th>
                                            <th
                                                class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider p-4">
                                                Approved/Rejected By
                                            </th>
                                            <th
                                                class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider p-4">
                                                Date & Time
                                            </th>
                                            <th
                                                class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider p-4">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-medium">
                                                        C
                                                    </div>
                                                    <span class="ml-2 text-gray-700">Cole Rogers</span>
                                                </div>
                                            </td>
                                            <td class="p-4 text-gray-700">Cole Rogers</td>
                                            <td class="p-4 text-gray-700">08/13/2024 01:58 PM</td>
                                            <td class="p-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-medium">
                                                        K
                                                    </div>
                                                    <span class="ml-2 text-gray-700">Kevin Garvin</span>
                                                </div>
                                            </td>
                                            <td class="p-4 text-gray-700">Paul DeSisto</td>
                                            <td class="p-4 text-gray-700">08/13/2024 02:16 PM</td>
                                            <td class="p-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            </td>
                                        </tr>
                                        <!-- Add other approvers similarly -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                    <!-- Non-Financial Change Content -->
                    <template x-if="selectedItem?.title === 'Non-Financial Change'">
                        <div>
                            <div class="border rounded-md">
                                <div class="p-4 border-b bg-gray-50">
                                    <h3 class="font-medium text-gray-700">Non-Financial Change Information</h3>
                                </div>

                                <div class="p-4 space-y-4">
                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">TIMESHEET APPROVING MANAGER</div>
                                        <div class="line-through text-red-500">Hannah Young</div>
                                        <div class="text-green-500">Gloria Hall</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">HIRING MANAGER</div>
                                        <div class="line-through text-red-500">Hannah Young</div>
                                        <div class="text-green-500">Gloria Hall</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">CONTRACTOR PORTAL ID</div>
                                        <div class="text-gray-700">WK00002429</div>
                                        <div class="text-gray-700">WK00002429</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">GL ACCOUNT:</div>
                                        <div class="text-gray-700">507105-Temporary Help</div>
                                        <div class="text-gray-700">507105-Temporary Help</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">TIMESHEET ENTRY</div>
                                        <div></div>
                                        <div></div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">LABOR TYPE</div>
                                        <div class="text-gray-700">Referred</div>
                                        <div class="text-gray-700">Referred</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">CANDIDATE SOURCING TYPE & WORKER TYPE</div>
                                        <div class="text-gray-700">Direct Sourced</div>
                                        <div class="text-gray-700">Direct Sourced</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">VENDOR ACCOUNT MANAGER</div>
                                        <div class="text-gray-700">Rachel Coy</div>
                                        <div class="text-gray-700">Rachel Coy</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">LOCATION</div>
                                        <div class="line-through text-red-500">US San Jose 160 West Santa Clara
                                            Street</div>
                                        <div class="text-green-500">US Bradenton 8430 Enterprise Circle</div>
                                    </div>

                                    <div
                                        class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 border-b pb-3">
                                        <div class="text-gray-600">LOCATION TAX (%)</div>
                                        <div class="text-gray-700">0.00</div>
                                        <div class="text-gray-700">0.00</div>
                                    </div>

                                    <div class="grid grid-cols-[200px,1fr,1fr] items-center gap-4 pb-3">
                                        <div class="text-gray-600">ORIGINAL START DATE</div>
                                        <div class="text-gray-700">04/11/2023</div>
                                        <div class="text-gray-700">04/11/2023</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="text-gray-600 mb-2">Business Justification:</div>
                                <p class="text-gray-700">Gloria Hall is the new appropriate manager for
                                    timesheet approval and all other needs for Allison.</p>
                            </div>
                        </div>
                    </template>

                    <!-- Rate Change Content -->
                    <template x-if="selectedItem?.title === 'Rate Change'">
                        <div class="space-y-4">
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

                    <!-- Extension Request Content -->
                    <template x-if="selectedItem?.title === 'Extension'">
                        <div class="space-y-4">
                            <!-- Extension Details -->
                            <div class="bg-white border rounded-lg">
                                <div class="border-b p-4">
                                    <h3 class="text-gray-700 font-medium">Extension Details</h3>
                                </div>

                                <div class="divide-y">
                                    <!-- End Date -->
                                    <div class="p-4">
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <div class="text-gray-600 mb-2">End Date</div>
                                                <div class="line-through text-red-500">11/17/2023</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 mb-2">End Date</div>
                                                <div class="text-green-500">11/17/2024</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rates -->
                                    <div class="p-4">
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <div class="text-gray-600 mb-2">Rates</div>
                                                <div class="space-y-1">
                                                    <div class="text-gray-700">RT: $57.50</div>
                                                    <div class="text-gray-700">OT: $86.25</div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 mb-2">Rates</div>
                                                <div class="space-y-1">
                                                    <div class="text-gray-700">RT: $57.50</div>
                                                    <div class="text-gray-700">OT: $86.25</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Budget -->
                                    <div class="p-4">
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <div class="text-gray-600 mb-2">Budget</div>
                                                <div class="line-through text-red-500">$103,800.00</div>
                                            </div>
                                            <div>
                                                <div class="text-gray-600 mb-2">Budget</div>
                                                <div class="text-green-500">$223,100.00</div>
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
                                        <div class="text-gray-700">$119,600.00</div>
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
                                        <div class="text-gray-700">Assignment Extension</div>
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
                                        <div class="text-gray-700">The assignment has been extended.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approval Work Flow Notice -->
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <div class="text-blue-700">
                                    It was accepted by Rachel Coy on 11/16/2023 09:14 AM.
                                </div>
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
                                                        H
                                                    </div>
                                                    <span class="text-gray-700">Hannah Young</span>
                                                </div>
                                            </td>
                                            <td class="p-4 text-gray-700">Hannah Young</td>
                                            <td class="p-4">N/A</td>
                                            <td class="p-4">N/A</td>
                                            <td class="p-4 text-gray-700">11/06/2023 04:47 PM</td>
                                            <td class="p-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            </td>
                                        </tr>
                                        <!-- Add other approvers similarly -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                    <!-- Job History Content -->
                    <template x-if="selectedItem?.title === 'Job History'">
                        <div class="space-y-4">
                            <!-- Header with Job Title and Status -->
                            <div class="bg-white border rounded-lg">
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-medium">
                                            <span>P</span>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">Paralegal</h3>
                                            <p class="text-sm text-gray-500">Paralegal (5403)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div class="bg-white border rounded-lg">
                                <div class="divide-y">
                                    <!-- Status -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">STATUS</div>
                                        <div>
                                            <span
                                                class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                Pending - PMO
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">ACTION</div>
                                        <div class="text-gray-900">Job Created</div>
                                    </div>

                                    <!-- Job Profile -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">JOB PROFILE</div>
                                        <div class="text-green-500">Paralegal</div>
                                    </div>

                                    <!-- Category -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">CATEGORY</div>
                                        <div class="text-green-500">Admin/Clerical</div>
                                    </div>

                                    <!-- Hiring Manager -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">HIRING MANAGER</div>
                                        <div class="text-green-500">Aaron Loan</div>
                                    </div>

                                    <!-- Work Location -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">WORK LOCATION</div>
                                        <div class="text-green-500">US Pittsburgh 11 Stanwix Street</div>
                                    </div>

                                    <!-- Number of Opening(s) -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">NUMBER OF OPENING(S)</div>
                                        <div class="text-green-500">17</div>
                                    </div>

                                    <!-- Expenses Allowed -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">EXPENSES ALLOWED</div>
                                        <div class="text-green-500">Yes</div>
                                    </div>

                                    <!-- Estimated Job Start Date -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">ESTIMATED JOB START DATE</div>
                                        <div class="text-gray-900">11/04/2024</div>
                                    </div>

                                    <!-- Estimated Job End Date -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">ESTIMATED JOB END DATE</div>
                                        <div class="text-gray-900">03/08/2025</div>
                                    </div>

                                    <!-- Minimum Bill Rate -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">MINIMUM BILL RATE</div>
                                        <div class="text-green-500">$39.80</div>
                                    </div>

                                    <!-- Maximum Bill Rate -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">MAXIMUM BILL RATE</div>
                                        <div class="text-green-500">$46.83</div>
                                    </div>

                                    <!-- Estimated Hours / Day -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">ESTIMATED HOURS / DAY</div>
                                        <div class="text-green-500">8.00</div>
                                    </div>

                                    <!-- Work Days / Week -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">WORK DAYS / WEEK</div>
                                        <div class="text-green-500">5.00</div>
                                    </div>

                                    <!-- Pre-identified Candidate -->
                                    <div class="grid grid-cols-[150px,1fr] p-4 items-center">
                                        <div class="text-gray-600">PRE-IDENTIFIED CANDIDATE?</div>
                                        <div class="text-green-500">No</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.timeline = {
        init() {
            this.items = [
                {
                    date: new Date('2024-08-21T14:46:00'),
                    title: 'Non-Financial Change',
                    description: 'Non-Financial Change updated by',
                    author: 'Hannah Young',
                    id: '#4764'
                },
                {
                    date: new Date('2024-08-13T16:29:00'),
                    title: 'Additional Budget',
                    description: 'Additional Budget updated by',
                    author: 'Hannah Young',
                    id: '#4664'
                },
                {
                    date: new Date('2024-08-13T13:38:00'),
                    title: 'Additional Budget',
                    description: 'Additional Budget updated by',
                    author: 'Hannah Young',
                    id: '#4654'
                },
                {
                    date: new Date('2024-07-26T13:19:00'),
                    title: 'Rate Change',
                    description: 'Rate Change updated by',
                    author: 'Paul DeSisto',
                    id: '#4491'
                },
                {
                    date: new Date('2024-07-26T12:29:00'),
                    title: 'Rate Change',
                    description: 'Rate Change updated by',
                    author: 'Hannah Young',
                    id: '#4488'
                },
                {
                    date: new Date('2024-07-26T12:29:00'),
                    title: 'Extension',
                    description: 'Extension updated by',
                    author: 'Hannah Young',
                    id: '#4488'
                },
                {
                    date: new Date('2024-07-26T12:29:00'),
                    title: 'Job History',
                    description: 'Extension updated by',
                    author: 'Hannah Young',
                    id: '#4488'
                }
            ],
                this.isOpen = false,
                this.selectedItem = null,

                this.openSidebar = (item) => {
                    this.selectedItem = item;
                    this.isOpen = true;
                },

                this.closeSidebar = () => {
                    this.isOpen = false;
                    setTimeout(() => {
                        this.selectedItem = null;
                    }, 300); // Wait for transition to complete
                },

                this.getSidebarTitle = (item) => {
                    if (!item) return '';
                    switch (item.title) {
                        case 'Additional Budget':
                            return 'Additional Budget Request';
                        case 'Non-Financial Change':
                            return 'Non-Financial Change Information';
                        case 'Rate Change':
                            return 'Assignment Amendment Request (Rate Change)';
                        default:
                            return item.title;
                    }
                },

                this.formatDate = (date) => {
                    const pad = (n) => n < 10 ? '0' + n : n;
                    const hours = date.getHours();
                    const minutes = date.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const formattedHours = hours % 12 || 12;

                    return `${pad(date.getMonth() + 1)}/${pad(date.getDate())}/${date.getFullYear()} ${pad(formattedHours)}:${pad(minutes)} ${ampm}`;
                }
        }
    }
</script>