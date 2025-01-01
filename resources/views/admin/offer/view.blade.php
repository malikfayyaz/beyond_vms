@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            @include('admin.layouts.partials.alerts')
          <div class="mb-8">
            <div class="w-full">
              <!-- <div class="w-full space-y-4">
                <div class="shadow-[0_3px_10px_rgb(248,113,113,0.2)] bg-red-400 rounded w-full p-6">
                  <div class="flex gap-6 items-center">
                        <ul class="text-white">
                        <li>
                            <span class="font-bold">Reason for Withdrawn:</span>
                            Rate is Incorrect
                        </li>
                        <li>
                            <span class="font-bold">Notes:</span>
                            Testing notes
                        </li>
                        <li>
                            <span class="font-bold">Withdrawn By:</span>
                            System Admin
                        </li>
                        <li>
                            <span class="font-bold">Withdrawn Date & Time:</span>
                            08/15/2024 03:52 PM
                        </li>
                        </ul>
                  </div>
                </div>
              </div> -->
                <!--    submitForm() {
                        if (this.validateForm()) {
                            console.log('Form submitted successfully');
                            this.openModal = false;
                        }
                    },-->
 <div class="mx-4 pt-0 pb-0 px-8 rounded">
    <div x-data="contractForm" class="w-full flex justify-end items-center gap-4">
    @if (!in_array($offer->status, [13, 2, 3]))
    <button
        @click="actionType = 'Reject'; openModal = true; currentRowId = {{ $offer->id }}; submitForm(currentRowId, actionType);"
        type="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 capitalize">
        {{translate('Reject Offer')}}
    </button>
    <button
        @click="actionType = 'Withdraw'; openModal = true; currentRowId = {{ $offer->id }}; submitForm(currentRowId, actionType);"
        type="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 capitalize">
        {{translate('Withdraw Offer')}}
    </button>
    @endif
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
        <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white" @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between border-b p-4">
                <h2 class="text-xl font-semibold">
                  <span x-text="actionType === 'Reject' ? 'Reject Offer' : 'Withdraw Offer'"></span></h2>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 bg-transparent hover:bg-transparent">
                    &times;
                </button>
            </div>
            <!-- Content -->
            <div class="p-4">
                <form @submit.prevent="submitForm(currentRowId, actionType)" id="generalformwizard">
                    <div x-show="actionType === 'Reject'" class="mt-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Reason for Rejection <span class="text-red-500">*</span>
                        </label>
                        <select id="reason" x-model="reason" class="w-full">
                            <option value="">Select</option>
                            @foreach (checksetting(17) as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="errors.reason" class="text-red-500 text-sm mt-1" x-text="errors.reason"></p>
                    </div>
                    <div x-show="actionType === 'Withdraw'" class="mt-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Reason for Withdraw <span class="text-red-500">*</span>
                        </label>
                        <select id="reason" x-model="reason" class="w-full">
                            <option value="">Select</option>
                            @foreach (checksetting(17) as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="errors.reason" class="text-red-500 text-sm mt-1" x-text="errors.reason"></p>
                    </div>
                    <div class="mt-4">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                            Note <span class="text-red-500">*</span>
                        </label>
                        <textarea id="note" x-model="note" rows="4" class="w-full border border-gray-300 rounded-md"></textarea>
                        <p x-show="errors.note" class="text-red-500 text-sm mt-1" x-text="errors.note"></p>
                    </div>
                </form>
            </div>
            <!-- Footer -->
            <div class="flex justify-end space-x-2 border-t p-4">
                <button type="button" @click="openModal = false" class="rounded-md bg-gray-200 px-4 py-2">
                    Close
                </button>
                <button type="button" @click="submitForm(currentRowId, actionType)" class="rounded-md bg-green-500 px-4 py-2 text-white">
                    Save
                </button>
            </div>
        </div>
    </div>
    </div>
            <!-- End model -->
  <div x-data="{
    openModal: false,
    currentRowId: null,
        actionType: '',
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
        formData.append('actionType', this.actionType); //for button
        if (this.actionType === 'Reject') {
                formData.append('reason', this.reason);
            }
        formData.append('note', this.note);

        // Get the file input element and append the file if it exists
        const fileInput = document.getElementById('jobAttachment');
        if (fileInput.files.length > 0) {
            formData.append('jobAttachment', fileInput.files[0]); // Append the first selected file
        }
        // Call the AJAX function
                        const url = '/admin/offer/offerworkflowAccept';
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
                            {{translate('Offer Workflow')}}
                        </h2>
                    </div>
                    <div class="bg-white shadow rounded-lg">
                        <div class="overflow-hidden">
                            <table class="w-full">
                                <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('S.NO')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approver Name')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approver Type')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approved/Rejected By')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approved/Rejected Date & Time')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approval Notes')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Approval Document')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Status')}}</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">{{translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @if($workflows->isEmpty())
                                    <tr>
                                        <td colspan="9" class="py-4 px-4 text-center text-sm text-gray-600">
                                            No workflows available.
                                        </td>
                                    </tr>
                                @else
                                    @foreach($workflows as $index => $workflow)
                                        <tr>
                                            <td class="py-4 px-4 text-center text-sm">{{ $index + 1 }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->hiringManager->full_name }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approve_reject_type }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approve_reject_by ?? 'N/A' }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ formatDateTime($workflow->approved_datetime) ?? 'N/A' }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approval_notes ?? 'N/A' }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->approval_doc ?? 'N/A' }}</td>
                                            <td class="py-4 px-4 text-center text-sm">{{ $workflow->status }}</td>
                                            <td class="py-4 px-4 text-center text-sm">
                                                @if($workflow->hiringManager->user_id == auth()->user()->id && $workflow->status == 'Pending' && $workflow->email_sent == '1')
                                                    <button
                                                        @click="actionType = 'Accept'; openModal = true; currentRowId = {{ $workflow->id }}; submitForm(currentRowId, actionType);"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        {{translate('Accept')}}
                                                    </button>
                                                    <button
                                                        @click="actionType = 'Reject'; openModal = true; currentRowId = {{ $workflow->id }}; submitForm(currentRowId, actionType);"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        {{translate('Reject')}}
                                                    </button>
                                                @endif
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
                                                @foreach ($rejectionreason as $key => $reason)
                                                    <option value="{{ $key }}">{{ $reason }}</option>
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







                <div class="flex w-full gap-4 mt-4">
                <!-- Left Column -->
                <div
                  class="w-1/2 p-[30px] rounded border"
                  :style="{'border-color': 'var(--primary-color)'}"
                >
                  <h3 class="flex items-center gap-2 mb-4">
                    <i
                      class="fa-regular fa-address-card"
                      :style="{'color': 'var(--primary-color)'}"
                    ></i
                    ><span :style="{'color': 'var(--primary-color)'}"
                      >{{translate('Offer Details (Offer ID:')}}{{$offer->id}})</span
                    >
                  </h3>
                  <div class="flex flex-col">
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Status:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">
                          <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white bg-purple-400">
                              {{$offer->getOfferStatus($offer->status)}}</span
                          >
                        </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Contractor Name:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light capitalize font-semibold">
                          <a href="#" class="text-blue-400">{{$offer->consultant->full_name}}</a>
                        </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Hiring Manager:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->hiringManager->fullname}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">
                            {{translate('Timesheet Approving Manager:')}}
                        </h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->hiringManager->full_name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Vendor:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">
                          {{$offer->vendor->full_name}}
                        </p>
                      </div>
                    </div>

                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Division:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->division->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Region:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->regionZone->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Remote:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->remote_option}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4" >
                        <h4 class="font-medium">{{translate('Job Profile:')}}</h4>
                      </div>
                      <div class="w-2/4" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
                        <p class="font-light">
                          <a class="text-blue-400 font-semibold cursor-pointer"
                            onclick="openJobDetailsModal({{ $offer->careerOpportunity->id }})"
                            >{{$offer->careerOpportunity->title}} ({{$offer->careerOpportunity->id}})</a
                          >
                        </p>
                        <x-job-details />
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Job Duration:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->date_range }}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">
                            {{translate('Job Budget (All Resources Cost):')}}
                        </h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">${{$offer->careerOpportunity->all_resources_total_cost}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Client Billable:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->careerOpportunity->client_billable}} </p>
                      </div>
                    </div>
                    <!-- Offer Dates and Location -->
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <h3 class="flex items-center gap-2">
                        <i
                          class="fa-regular fa-address-card"
                          :style="{'color': 'var(--primary-color)'}"
                        ></i
                        ><span :style="{'color': 'var(--primary-color)'}"
                          >{{translate('Offer Dates and Location')}}</span
                        >
                      </h3>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Start Date:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->start_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('End Date:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->end_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">{{translate('Location:')}}</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->CareerOpportunity->location->LocationDetails}}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Right Column -->
                <div
                  class="w-1/2 p-[30px] rounded border"
                  :style="{'border-color': 'var(--primary-color)'}"
                >
                  <h3 class="flex items-center gap-2 mb-4">
                    <i
                      class="fa-regular fa-money-bill-1"
                      :style="{'color': 'var(--primary-color)'}"
                    ></i
                    ><span :style="{'color': 'var(--primary-color)'}"
                      >{{translate('Offer Rates')}}</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <h3 class="flex items-center gap-2">
                      <i
                        class="fa-solid fa-cash-register"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i
                      ><span :style="{'color': 'var(--primary-color)'}"
                        >{{translate('Bill Rate (For Vendor)')}}</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{translate('Bill Rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->offer_bill_rate}} </p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{translate('Over Time Rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->over_time}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <h3 class="flex items-center gap-2">
                      <i
                        class="fa-solid fa-cash-register"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i
                      ><span :style="{'color': 'var(--primary-color)'}"
                        >{{translate('Bill Rate (For Vendor)')}}</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{translate('Bill Rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_bill_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{translate('Over Time Rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_overtime}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{translate('Regular Hours Estimated Cost:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->careerOpportunity->regular_hours_cost}} </p>
                    </div>
                  </div>
                  @if(!empty($offer->offer_details))
                    @php
                      $offerDetails = json_decode($offer->offer_details, true); // Decode JSON into an array
                    @endphp
                    <div class="flex items-center justify-between py-4 border-t">
                      <h3 class="flex items-center gap-2">
                        <i
                          class="fa-solid fa-cash-register"
                          :style="{'color': 'var(--primary-color)'}"
                        ></i
                        ><span :style="{'color': 'var(--primary-color)'}"
                          >Data</span
                        >
                      </h3>
                    </div>
                    @foreach ($offerDetails as $key => $value)
                      <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                          <h4 class="font-medium">{{ $key }}:</h4>
                        </div>
                        <div class="w-2/4">
                          @if(is_array($value))
                              {{-- Handle array values --}}
                              <p class="font-light">{{ implode(', ', $value) }}</p>
                          @else
                              {{-- Handle scalar values --}}
                              <p class="font-light">{{ $value }}</p>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>
              </div>
              <div
                class="p-[30px] rounded border mt-4"
                :style="{'border-color': 'var(--primary-color)'}"
              >
                <div class="mb-4 flex items-center gap-2">
                  <i
                    class="fa-solid fa-clock-rotate-left"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>
                  <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                  >
                      {{translate('Offer History')}}
                  </h2>
                </div>
                <div style="overflow: scroll;">
                  <table id="listing"
                    class="min-w-full bg-white shadow-md rounded-lg overflow-hidden" style="width: max-content;"
                  >
                    <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <!-- Status -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Status')}}
                        </th>
                        <!-- User -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Offer ID')}}
                        </th>
                        <!-- job -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Contractor Name')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Job Profile')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Hiring Manger')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Vendor')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Offer Date')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Bill Rate')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Workorder Status')}}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Woker Type')}}
                        </th>
                        <th style="width: 80px"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{translate('Action')}}
                        </th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>

     document.addEventListener('DOMContentLoaded', function() {
        if (window.$) {
            let currentType = 'all_offers';
            let currentId = '{{$offer->id}}';
            let subId = '{{$offer->submission_id}}';

            let table = initializeDataTable('#listing', '/admin/offer/index', [
                { data: 'status', name: 'status' },
                { data: 'id', name: 'id' },
                { data: 'consultant_name', name: 'consultant_name' },
                { data: 'career_opportunity', name: 'career_opportunity' },
                { data: 'hiring_manger', name: 'hiring_manger' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'offer_bill_rate', name: 'offer_bill_rate' },
                { data: 'wo_status', name: 'wo_status' },
                { data: 'worker_type', name: 'worker_type' },
                { data: 'action', name: 'action', orderable: false, searchable: false }

            ], () => ({ currentType, currentId, subId }));
          }
        });
      function openJobDetailsModal(jobId) {
        fetch(`/job-details/${jobId}`)
            .then(response => response.json())
            .then(data => {
                const event = new CustomEvent('job-details-updated', {
                        detail: data,
                        bubbles: true,
                        composed: true
                    });
                    // console.log(event.detail.data);

                    document.dispatchEvent(event);
            })
            .catch(error => console.error('Error:', error));
      }
    </script>
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contractForm', () => ({
            openModal: false,
            actionType: '',
            offerId: '{{ $offer->id }}',
            reason: '',
            note: '',
            errors: {},

            validateForm() {
                this.errors = {};
                if (this.actionType === 'Reject' && !this.reason) {
                    this.errors.reason = 'Please select a reason';
                }
                if (this.actionType === 'Withdraw' && !this.reason) {
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
                    formData.append('offerId', this.offerId);
                    formData.append('actionType', this.actionType); //for button
                    if (this.actionType === 'Reject') {
                        formData.append('reason', this.reason);
                    }
                    if (this.actionType === 'Withdraw') {
                        formData.append('reason', this.reason);
                    }
                    formData.append('note', this.note);
                    console.log(this.actionType);
                    const url = '{{ route('admin.offer.offerRejectWithdraw') }}';
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

@endsection
