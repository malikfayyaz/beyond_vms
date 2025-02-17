@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
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
                <div  x-data="{
                openModal: false,
                currentRowId: null
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
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @if($workflows->isEmpty())
                                    <tr>
                                        <td colspan="9" class="py-4 px-4 text-center text-sm text-gray-600">
                                            {{translate('No workflows available.')}}
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
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal -->
                    <!-- Modal -->
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
                      >{{translate('Offer Details')}}</span
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
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white bg-purple-400"
                            >{{$offer->getOfferStatus($offer->status)}}</span
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
                      <div class="w-2/4">
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
                        >{{translate('Bill Rate (For Client)')}}</span
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
                  @if(!empty($offer->offer_details) && $offer->offer_details != '[]')
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
                          >{{translate('Additional Data')}}</span
                        >
                      </h3>
                    </div>
                    @php
                        $fieldLabels = collect($formFields)->pluck('label', 'name')->toArray();
                    @endphp
                    @foreach ($offerDetails as $key => $value)
                      <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                          <h4 class="font-medium">{{ $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</h4>
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
                <div  style="overflow: scroll;">
                  <table  id="listing"
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
                @if($offer->status == '4')
                  <div class="mt-4">
                    <button
                        type="button"
                        @click="acceptOffer({{ $offer->id }},'accept')"
                        aria-label="Accept Offer"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-red-600 capitalize"
                    >
                        {{translate('Accept Offer')}}
                    </button>
                    <button
                        type="button"
                        @click="acceptOffer({{ $offer->id }},'reject')"
                        aria-label="Reject Offer"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize"
                    >
                        {{translate('Reject Offer')}}
                    </button>
                  </div>
                @endif


          </div>
        </div>
    </div>

@endsection
<script>
      document.addEventListener('DOMContentLoaded', function() {
        if (window.$) {
            let currentType = 'all_offers';
            let currentId = '{{$offer->id}}';
            let subId = '{{$offer->submission_id}}';

            let table = initializeDataTable('#listing', '/vendor/offer/index', [
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

      function acceptOffer(id,status) {
        let formData = new FormData();
        formData.append('offer_id', id);
        formData.append('accept_reject', status);
        ajaxCall('/vendor/offer/accept-offer', 'POST', [[onSuccess, ['response']]], formData);
      }


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
