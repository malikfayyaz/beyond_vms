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
                            Offer Workflow
                        </h2>
                    </div>
                    <div class="bg-white shadow rounded-lg">
                        <div class="overflow-hidden">
                            <table class="w-full">
                                <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">S.NO</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approver Name</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approver Type</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approved/Rejected By</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approved/Rejected Date & Time</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approval Notes</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Approval Document</th>
                                    <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Status</th>
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
                                        <td class="py-4 px-4 text-center text-sm">{{ $workflow->approved_datetime ?? 'N/A' }}</td>
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
                      >Offer Details (Offer ID:4713)</span
                    >
                  </h3>
                  <div class="flex flex-col">
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Status:</h4>
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
                        <h4 class="font-medium">Contractor Name:</h4>
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
                        <h4 class="font-medium">Hiring Manager:</h4>
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
                          Timesheet Approving Manager:
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
                        <h4 class="font-medium">Vendor:</h4>
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
                        <h4 class="font-medium">Division:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->division->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Region:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->regionZone->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Remote:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->remote_option}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Job Profile:</h4>
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
                        <h4 class="font-medium">Job Duration:</h4>
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
                          Job Budget (All Resources Cost):
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
                        <h4 class="font-medium">Client Billable:</h4>
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
                          >Offer Dates and Location</span
                        >
                      </h3>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Start Date:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->start_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">End Date:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->end_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Location:</h4>
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
                      >Offer Rates</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <h3 class="flex items-center gap-2">
                      <i
                        class="fa-solid fa-cash-register"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i
                      ><span :style="{'color': 'var(--primary-color)'}"
                        >Bill Rate (For Vendor)</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">Bill Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->offer_bill_rate}} </p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">Over Time Rate:</h4>
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
                        >Bill Rate (For Vendor)</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Bill Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_bill_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Over Time Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_overtime}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Regular Hours Estimated Cost:</h4>
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
                    Offer History
                  </h2>
                </div>
                <div x-data="catalogTable()" style="overflow: scroll;">
                  <table
                    class="min-w-full bg-white shadow-md rounded-lg overflow-hidden" style="width: max-content;"
                  >
                    <thead class="bg-gray-200 text-gray-700">
                      <tr>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th
                          @click="sort('catalogName')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Offer ID
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'catalogName' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'catalogName' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('category')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Contractor Name
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'category' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'category' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('profileWorkerType')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Job ID
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'profileWorkerType' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'profileWorkerType' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Hiring Manager
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Vendor
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Offer Date
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Bill Rate
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th class="py-3 px-4 text-left">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="item in paginatedItems" :key="item.id">
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4" x-text="item.status"></td>
                          <td class="py-3 px-4" x-text="item.offerID"></td>
                          <td class="py-3 px-4" x-text="item.candidate"></td>
                          <td class="py-3 px-4" x-text="item.jobID"></td>
                          <td class="py-3 px-4" x-text="item.hiringManager"></td>
                          <td class="py-3 px-4" x-text="item.vendor"></td>
                          <td class="py-3 px-4" x-text="item.date"></td>
                          <td class="py-3 px-4" x-text="item.offerBillRate"></td>
                          <td class="py-3 px-4">
                            <button
                              class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-eye"></i>
                            </button>
                            <button
                              class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-edit"></i>
                            </button>
                            <button
                              @click="deleteItem(item.id)"
                              class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
                @if($offer->status == '4')
                    <button
                        type="button"
                        @click="acceptOffer({{ $offer->id }},'accept')"
                        aria-label="Accept Offer"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize"
                    >
                        Accept Offer
                    </button>
                    <button
                        type="button"
                        @click="acceptOffer({{ $offer->id }},'reject')"
                        aria-label="Reject Offer"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize"
                    >
                        Reject Offer
                    </button>
                @endif


          </div>
        </div>
    </div>

@endsection
<script>
      function catalogTable() {
        const logs = @json($logs);
        return {
            items: logs.map(log => ({
                id: log.id,  // Log ID
                status: log.properties.attributes.status_name,
                offerID: log.properties.attributes.id, // Example usage of status for catalog name
                candidate: log.properties.attributes.candidate_name, 
                jobID: log.properties.attributes.career_opportunity_id, 
                hiringManager: log.properties.attributes.hiring_manager_name, 
                vendor: log.properties.attributes.vendor_name, 
                date: log.properties.attributes.start_date, 
                offerBillRate: log.properties.attributes.offer_bill_rate, 
                
                  // Using status from the logs
            })),
            sortColumn: "id",
            sortDirection: "asc",
            itemsPerPage: 10,
            currentPage: 1,
            get paginatedItems() {
              const start = (this.currentPage - 1) * this.itemsPerPage;
              const end = start + this.itemsPerPage;
              return this.items.slice(start, end);
            },
          
          sort(column) {
            if (this.sortColumn === column) {
              this.sortDirection =
                this.sortDirection === "asc" ? "desc" : "asc";
            } else {
              this.sortColumn = column;
              this.sortDirection = "asc";
            }
          },
         
          deleteItem(id) {
            
          },
          
        };
      }

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
