@extends('admin.layouts.app')
@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    
    <div class="bg-white mx-4 my-8 rounded p-8">
    @include('admin.layouts.partials.alerts')
          <!-- Cards -->
          <div class="mb-8">
            <div class="flex gap-4 w-full">
              <div class="w-full space-y-4">
                <!-- First Tab-->
                <div
                  x-data="{
                          id: 1,
                          get expanded() {
                              return this.active === this.id
                          },
                          set expanded(value) {
                              this.active = value ? this.id : null
                          },
                      }"
                  role="region"
                  class="bg-white"
                >
                  <h2>
                    <button
                      type="button"
                      x-on:click="expanded = !expanded"
                      :aria-expanded="expanded"
                      class="flex w-full items-center justify-between px-6 py-4 text-xl font-bold"
                    >
                      <div class="flex items-center gap-4">
                        <i class="fa-solid fa-circle-info text-white"></i>
                        <h2 class="text-xl font-bold text-white">
                          Job Information
                        </h2>
                      </div>
                      <span
                        x-show="expanded"
                        aria-hidden="true"
                        class="ml-4 text-white"
                        >&minus;</span
                      >
                      <span
                        x-show="!expanded"
                        aria-hidden="true"
                        class="ml-4 text-white"
                        >&plus;</span
                      >
                    </button>
                  </h2>

                  <div
                    x-show="expanded"
                    x-collapse
                    class="py-4 px-2 border"
                    :style="{'border-color': 'var(--primary-color)'}"
                  >
                    <!-- Row 1-->
                    <div class="flex gap-4 w-full mb-4">
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-briefcase text-[#28c76f]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#28c76f]">Job</span>
                            <span>{{$submission->careerOpportunity->title}} ({{$submission->careerOpportunity->id}})</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#00bad1]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#00bad1]"
                              >Job Minimum Bill Rate</span
                            >
                            <span>${{$submission->careerOpportunity->min_bill_rate}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#FFF0E1] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-user text-[#ff9f43]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#ff9f43]"
                              >Division</span
                            >
                            <span>{{$submission->careerOpportunity->division->name}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#E9E7FD] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-regular fa-clock text-[#7367f0]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#7367f0]"
                              >Job Duration</span
                            >
                            <span>{{$submission->careerOpportunity->date_range}}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Row 2-->
                    <div class="flex gap-4 w-full mb-4">
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#28c76f]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#28c76f]"
                              >Job Maximum Bill Rate</span
                            >
                            <span>${{$submission->careerOpportunity->max_bill_rate}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-map-pin text-[#00bad1]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#00bad1]">Region</span>
                            <span>{{$submission->careerOpportunity->regionZone->name}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#FFF0E1] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#ff9f43]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#ff9f43]"
                              >Job Budget</span
                            >
                            <span>${{$submission->careerOpportunity->single_resource_total_cost}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#E9E7FD] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#7367f0]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#7367f0]"
                              >Single Resource Cost</span
                            >
                            <span>${{$submission->careerOpportunity->all_resources_total_cost}}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Second Tab-->
                <div
                  x-data="{
        id: 2,
        get expanded() {
            return this.active === this.id
        },
        set expanded(value) {
            this.active = value ? this.id : null
        },
    }"
                  role="region"
                  class="bg-white"
                >
                  <h2>
                    <button
                      type="button"
                      x-on:click="expanded = !expanded"
                      :aria-expanded="expanded"
                      class="flex w-full items-center justify-between px-6 py-4 text-xl font-bold"
                    >
                      <div class="flex items-center gap-4">
                        <i class="fa-solid fa-circle-info text-white"></i>
                        <h2 class="text-xl font-bold text-white">
                          Other Information
                        </h2>
                      </div>
                      <span
                        x-show="expanded"
                        aria-hidden="true"
                        class="ml-4 text-white"
                        >&minus;</span
                      >
                      <span
                        x-show="!expanded"
                        aria-hidden="true"
                        class="ml-4 text-white"
                        >&plus;</span
                      >
                    </button>
                  </h2>

                  <div
                    x-show="expanded"
                    x-collapse
                    class="py-4 px-2 border"
                    :style="{'border-color': 'var(--primary-color)'}"
                  >
                    <!-- Row 1-->
                    <div class="flex gap-4 w-full mb-4">
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-user text-[#28c76f]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#28c76f]"
                              >Hiring Manager</span
                            >
                            <span>{{$submission->careerOpportunity->hiringManager->full_name}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-user-tag text-[#00bad1]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#00bad1]"
                              >Candidate Name</span
                            >
                            <span>{{$submission->consultant->full_name}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#FFF0E1] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#ff9f43]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#ff9f43]"
                              >Submission Bill Rate</span
                            >
                            <span>${{$submission->vendor_bill_rate}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#E9E7FD] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-map-pin text-[#7367f0]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#7367f0]"
                              >Location</span
                            >
                            <span>{{$submission->location->location_details}}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Row 2-->
                    <div class="flex gap-4 w-full mb-4">
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i class="fa-solid fa-user text-[#28c76f]"></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#28c76f]"
                              >Vendor Name</span
                            >
                            <span>{{$submission->vendor->full_name}}</span>
                          </div>
                        </div>
                      </div>
                      <div
                        class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
                      >
                        <div class="flex gap-6 items-center">
                          <div
                            class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
                          >
                            <i
                              class="fa-solid fa-money-bill text-[#00bad1]"
                            ></i>
                          </div>
                          <div class="flex flex-col gap-2">
                            <span class="font-bold text-[#00bad1]"
                              >Submission Over Time Bill rate</span
                            >
                            <span>${{$submission->client_over_time_rate}}</span>
                          </div>
                        </div>
                      </div>
                      <div class="w-full p-6"></div>
                      <div class="w-full p-6"></div>
                    </div>
                  </div>
                </div>
                <!-- Business Unit & Business Percentage -->
                <div class="mt-4">
                  <div
                    class="p-[30px] rounded border"
                    :style="{'border-color': 'var(--primary-color)'}"
                  >
                    <div class="mb-4 flex items-center gap-4">
                      <i
                        class="fa-solid fa-inbox"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i>
                      <h2
                        class="text-xl font-bold"
                        :style="{'color': 'var(--primary-color)'}"
                      >
                        Business Unit
                      </h2>
                    </div>
                    <div
                      class="flex py-4 px-2 rounded rounded-b-none"
                      :style="{'background-color': 'var(--primary-color)'}"
                    >
                      <div class="w-3/5">
                        <span class="text-white">Business Unit</span>
                      </div>
                      <div class="w-2/5 text-center">
                        <span class="text-white">Budget Percentage</span>
                      </div>
                    </div>
                    <div
                      class="flex justify-between gap-2 py-4 px-2 border-x border-b"
                    >
                    @foreach($submission->careerOpportunity->careerOpportunitiesBu as $bu)
                      <div class="w-3/5 flex-wrap">
                        <span>{{$bu->buName->name}}</span>
                      </div>
                      <div class="w-2/5 text-center">
                        <span>{{$bu->percentage}}%</span>
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <!-- Form -->
                <div x-data="createOffer" id="generalformwizard">
                  <form id="createOffer" @submit.prevent="validateForm">
                    <!--  Dates and Other Information -->
                    <div class="my-4">
                      <div
                        class="p-[30px] rounded border"
                        :style="{'border-color': 'var(--primary-color)'}"
                      >
                        <div class="mb-4 flex items-center gap-4">
                          <i
                            class="fa-regular fa-calendar"
                            :style="{'color': 'var(--primary-color)'}"
                          ></i>
                          <h2
                            class="text-xl font-bold"
                            :style="{'color': 'var(--primary-color)'}"
                          >
                            Dates and Other Information
                          </h2>
                        </div>
                        <!-- Date Picker-->
                        <div class="flex space-x-4 mt-4" x-data="{
                            formData: {
                                startDate: '{{ old('startDate',  $submission->careerOpportunity->start_date ? \Carbon\Carbon::parse( $submission->careerOpportunity->start_date)->format('Y/m/d') : '') }}',
                                endDate: '{{ old('endDate',  $submission->careerOpportunity->end_date ? \Carbon\Carbon::parse( $submission->careerOpportunity->end_date)->format('Y/m/d') : '') }}',
                                approvingManager: '{{ old('approvingManager', $submission->careerOpportunity->hiring_manager ?? '') }}',
                            },
                            startDateError: '',
                        }">
                          <div class="flex-1">
                            <label for="startDate" class="block mb-2"
                              >Choose Start Date:
                              <span class="text-red-500">*</span></label
                            >
                            <input
                             x-model="formData.startDate"
                            x-ref="startDate"
                              id="startDate"
                              class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                              type="text"
                              placeholder="Select start date"
                              @focus="flatpickr($refs.startDate, {
                                dateFormat: 'Y/m/d', // Format as YYYY/MM/DD
                                defaultDate: formData.startDate, // Pre-fill with existing date
                                onChange: (selectedDates, dateStr) => formData.startDate = dateStr
                            })"
                            />
                            <p
                              class="text-red-500 text-sm mt-1"
                              x-text="startDateError"
                            ></p>
                          </div>
                          <div class="flex-1">
                            <label for="endDate" class="block mb-2"
                              >Choose End Date:
                              <span class="text-red-500">*</span></label
                            >
                            <input
                            x-model="formData.endDate"
                            x-ref="endDate"
                              id="endDate"
                              class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                              type="text"
                              placeholder="Select end date"
                              @focus="flatpickr($refs.endDate, {
                                dateFormat: 'Y/m/d', // Format as YYYY/MM/DD
                                defaultDate: formData.endDate, // Pre-fill with existing date
                                onChange: (selectedDates, dateStr) => formData.endDate = dateStr
                            })"
                            />
                            <p
                              class="text-red-500 text-sm mt-1"
                              x-text="endDateError"
                            ></p>
                          </div>
                          <div class="flex-1">
                            <label class="block mb-2"
                              >Timesheet Approving Manager
                              <span class="text-red-500">*</span></label
                            >
                            @php $clients_hiring = \App\Models\Client::where('profile_status', 1)
                        ->orderBy('first_name', 'ASC')
                        ->get(); @endphp
                            <select
                              class="w-full select2-single custom-style"
                              id="approvingManager" x-model="formData.approvingManager" x-ref="approvingManager"
                            >
                              <option value="">
                                Select timesheet approving manager
                              </option>
                              @foreach ($clients_hiring as $key => $value)
                            <option value="{{ $value->id }}" 
                            {{ $value->id == $submission->careerOpportunity->hiring_manager ? 'selected' : '' }}>{{  $value->first_name.' '.$value->last_name; }}</option>
                            @endforeach
                            </select>
                            <p
                              class="text-red-500 text-sm mt-1"
                              x-text="approvingManagerError"
                            ></p>
                          </div>
                        </div>
                        <div class="flex space-x-4 mt-4" x-data="{
                            formData: {
                                 location: '{{ old('location', $submission->careerOpportunity->location_id ?? '') }}',
                                 remote: '{{ old('remote', $submission->careerOpportunity->remote_option ?? '') }}',
                                 },
                           
                        }">
                          <div class="flex-1">
                            <label class="block mb-2"
                              >Location
                              <span class="text-red-500">*</span></label
                            >
                            @php $location = \App\Models\Location::byStatus();@endphp
                            <select x-model="formData.location"
                              class="w-full select2-single custom-style"
                              id="location" x-ref="location"
                            >
                              <option value="">Select location</option>
                              @foreach ($location as $key => $value)
                            <option value="{{ $value->id }}"
                            {{ $value->id == $submission->careerOpportunity->location_id ? 'selected' : '' }}>
                            {{ locationName($value->id) }}</option>
                            @endforeach
                            </select>
                            <p
                              class="text-red-500 text-sm mt-1"
                              x-text="locationError"
                            ></p>
                          </div>
                          <div class="flex-1">
                            <label class="block mb-2"
                              >Remote <span class="text-red-500">*</span></label
                            >
                            <select
                              class="w-full select2-single custom-style"
                              id="remote" x-model="formData.remote"
                            >
                              <option value="">Select work type remote?</option>
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                             
                            </select>
                            <p
                              class="text-red-500 text-sm mt-1"
                              x-text="remoteError"
                            ></p>
                          </div>
                          <div class="flex-1"></div>
                        </div>
                        <div class="flex space-x-4 mt-4">
                          <div class="flex-1">
                            <label class="block mb-2">Notes for Program</label>
                            <textarea
                              class="w-full border rounded"
                              rows="5"
                              :style="{'border-color': 'var(--primary-color)'}"
                            ></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Offer Rates -->
                    <div class="my-4">
                      <div
                        class="p-[30px] rounded border"
                        :style="{'border-color': 'var(--primary-color)'}"
                      >
                        <div class="mb-4 flex items-center gap-2">
                          <i
                            class="fa-solid fa-dollar-sign"
                            :style="{'color': 'var(--primary-color)'}"
                          ></i>
                          <h2
                            class="text-xl font-bold"
                            :style="{'color': 'var(--primary-color)'}"
                          >
                            Offer Rates
                          </h2>
                        </div>
                        <div class="flex space-x-4 mt-4">
                          <div class="flex-1">
                            <label for="billRate" class="block mb-2 capitalize"
                              >Bill Rate
                              <span class="text-red-500">*</span></label
                            >
                            <div>
                              <div class="relative">
                                <span
                                  class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                  >$</span
                                >
                                <input
                                  type="text"
                                  class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                  placeholder="00.00"
                                  id="billRate"
                                  x-model="billRate"
                                  @input="formatRate('billRate', $event)"
                                  @blur="formatRate('billRate', $event)"
                                />
                              </div>
                              <p
                                class="text-red-500 text-sm mt-1"
                                x-text="billRateError"
                              ></p>
                            </div>
                          </div>
                          <div class="flex-1">
                            <label for="payRate" class="block mb-2 capitalize"
                              >Pay Rate
                              <span class="text-red-500">*</span></label
                            >
                            <div>
                              <div class="relative">
                                <span
                                  class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                  >$</span
                                >
                                <input
                                  type="text"
                                  class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                  placeholder="00.00"
                                  id="payRate"
                                  x-model="payRate"
                                  @input="formatRate('payRate', $event)"
                                  @blur="formatRate('payRate', $event)"
                                />
                              </div>
                              <p
                                class="text-red-500 text-sm mt-1"
                                x-text="payRateError"
                              ></p>
                            </div>
                          </div>
                        </div>
                        <div class="flex space-x-4 mt-4">
                          <div class="flex-1">
                            <label for="overTime" class="block mb-2 capitalize"
                              >Over Time Rate for Client</label
                            >
                            <div class="relative">
                              <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                >$</span
                              >
                              <input
                                type="text"
                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                placeholder="00.00"
                                value="150.00"
                                disabled
                                id="overTime"
                              />
                            </div>
                          </div>
                          <div class="flex-1">
                            <label
                              for="doubleTime"
                              class="block mb-2 capitalize"
                              >Double Time Rate for Client</label
                            >
                            <div class="relative">
                              <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                >$</span
                              >
                              <input
                                type="text"
                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                placeholder="00.00"
                                value="200.00"
                                disabled
                                id="doubleRate"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="flex space-x-4 mt-4">
                          <div class="flex-1">
                            <label
                              for="overTimeCandidate"
                              class="block mb-2 capitalize"
                              >Over Time Rate for Candidate</label
                            >
                            <div class="relative">
                              <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                >$</span
                              >
                              <input
                                type="text"
                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                placeholder="00.00"
                                value="37.50"
                                disabled
                                id="overTimeCandidate"
                              />
                            </div>
                          </div>
                          <div class="flex-1">
                            <label
                              for="doubleTimeCandidate"
                              class="block mb-2 capitalize"
                              >Double Time Rate for Candidate</label
                            >
                            <div class="relative">
                              <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                >$</span
                              >
                              <input
                                type="text"
                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                placeholder="00.00"
                                value="50.00"
                                disabled
                                id="doubleTimeCandidate"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button
                      type="submit"
                      class="px-4 py-2 text-white capitalize rounded"
                      :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
                    >
                      create offer
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
    </div>
    <script>
      document.addEventListener("alpine:init", () => {
        Alpine.data("createOffer", () => ({
        startDate: "",
        endDate: '',
        approvingManager: '',
        location: '',
          remote: "",
          billRate: "",
          payRate: "",
          startDateError: "",
          endDateError: "",
          approvingManagerError: "",
          locationError: "",
          remoteError: "",
          billRateError: "",
          payRateError: "",

          init() {
            this.initDatePickers();
            this.initSelect2();
          },

          initDatePickers() {
            const startPicker = flatpickr("#startDate", {
              dateFormat: "Y/m/d",
              onChange: (selectedDates, dateStr) => {
                this.startDate = dateStr;
                this.startDateError = "";
                if (
                  this.endDate &&
                  new Date(dateStr) > new Date(this.endDate)
                ) {
                  this.endDate = "";
                  this.endDateError = "End date must be after start date";
                  endPicker.clear();
                }
                endPicker.set("minDate", dateStr);
              },
            });

            const endPicker = flatpickr("#endDate", {
              dateFormat: "Y/m/d",
              onChange: (selectedDates, dateStr) => {
                this.endDate = dateStr;
                this.endDateError = "";
              },
            });
          },

          initSelect2() {
            this.$nextTick(() => {
              $("#approvingManager")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this.approvingManager = e.params.data.id;
                  this.approvingManagerError = "";
                })
                .on("select2:unselect", () => {
                  this.approvingManager = "";
                });

              $("#location")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this.location = e.params.data.id;
                  this.locationError = "";
                })
                .on("select2:unselect", () => {
                  this.location = "";
                });

              $("#remote")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this.remote = e.params.data.id;
                  this.remoteError = "";
                })
                .on("select2:unselect", () => {
                  this.remote = "";
                });
            });
          },

          formatRate(field, event) {
            const input = event.target;
            const cursorPosition = input.selectionStart;
            const lengthBefore = this[field].length;
            let value = this[field].replace(/[^\d.]/g, "");
            let parts = value.split(".");

            // Handle the whole number part
            parts[0] = parts[0].replace(/^0+/, "") || "0";

            // Handle the decimal part
            if (parts.length > 1) {
              parts[1] = parts[1].slice(0, 2);
              while (parts[1].length < 2) parts[1] += "0";
            } else {
              parts[1] = "00";
            }

            const formattedValue = parts.join(".");
            this[field] = formattedValue;

            // Adjust cursor position
            let newPosition;
            if (cursorPosition <= parts[0].length) {
              // If cursor was in the whole number part, keep it there
              newPosition = cursorPosition;
            } else {
              // If cursor was in or after decimal part, place it after the last digit entered
              newPosition = Math.min(
                cursorPosition,
                parts[0].length +
                  1 +
                  (parts[1].match(/[1-9]/)
                    ? parts[1].match(/[1-9]/).index + 1
                    : 0)
              );
            }

            this.$nextTick(() => {
              input.setSelectionRange(newPosition, newPosition);
            });

            this.validateRate(field);
          },

          validateRate(field) {
            const value = this[field];
            const regex = /^\d+\.\d{2}$/;

            if (!value) {
              this[`${field}Error`] = `Please enter a ${
                field === "billRate" ? "Bill" : "Pay"
              } Rate.`;
            } else if (!regex.test(value)) {
              this[
                `${field}Error`
              ] = `Please enter a valid rate in the format 00.00`;
            } else {
              this[`${field}Error`] = "";
            }
          },

          validateForm(e) {
            let isValid = true;

            if (!this.startDate) {
              this.startDateError = "Please select a start date.";
              isValid = false;
            }

            if (!this.endDate) {
              this.endDateError = "Please select an end date.";
              isValid = false;
            } else if (new Date(this.startDate) > new Date(this.endDate)) {
              this.endDateError = "End date must be after start date";
              isValid = false;
            }

            if (!this.approvingManager) {
              this.approvingManagerError =
                "Please select timesheet approving manager.";
              isValid = false;
            }

            if (!this.location) {
              this.locationError = "Please select a location.";
              isValid = false;
            }

            if (!this.remote) {
              this.remoteError = "Please select remote option.";
              isValid = false;
            }

            this.validateRate("billRate");
            this.validateRate("payRate");
            if (this.billRateError || this.payRateError) {
              isValid = false;
            }

            if (!isValid) {
              e.preventDefault();
            } else {
              console.log("Form is valid. Submitting...");
              // Add your form submission logic here
            }
          },
        }));
      });
    </script>
    
@endsection