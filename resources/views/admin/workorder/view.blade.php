@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')

        <div class="bg-white mx-4 my-8 rounded p-8">
          <div class="py-2 flex justify-between items-center gap-2">
            <div>
              <h3 class="text-xl" :style="{'color': 'var(--primary-color)'}">
                My Web Application - Workorder ID: 5854
              </h3>
            </div>
            <div class="flex items-center gap-2">
              <button
                type="submit"
                class="px-4 py-2 text-white capitalize rounded"
                :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
              >
                Withdraw Workorder / Submission
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-white capitalize rounded"
                :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
              >
                Back to List of Workorders
              </button>
            </div>
          </div>
          <div class="flex w-full gap-4">
            <!-- Left Column -->
            <div
              class="w-1/3 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}"
            >
              <h3 class="flex items-center gap-2 mb-4 bg-">
                <i
                  class="fa-solid fa-circle-info"
                  :style="{'color': 'var(--primary-color)'}"
                ></i>
                <span :style="{'color': 'var(--primary-color)'}"
                  >Work order information</span
                >
              </h3>
              <div class="flex flex-col">
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">contractor name:</h4>
                  </div>
                  <div class="w-2/4">
                    <a
                      href="#"
                      class="font-light"
                      :style="{'color': 'var(--primary-color)'}"
                      >{{$workorder->consultant->full_name}}</a
                    >
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">offer ID:</h4>
                  </div>
                  <div class="w-2/4">
                    <a
                      href="#"
                      class="font-light"
                      :style="{'color': 'var(--primary-color)'}"
                      >{{$workorder->offer_id}}</a
                    >
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">Unique ID:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{$workorder->consultant->unique_id}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Workorder Status:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">
                      <span
                        class="px-2 inline-flex text-xs leading-5 text-white font-semibold rounded-full bg-green-500"
                        >{{$workorder->status}}</span
                      >
                    </p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">offer accepted date:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{formatDate($workorder->offer->offer_accept_date)}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">offer accepted by:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light"><?php 
                             if($workorder->offer->modified_by_type =="1" ){
                      $admin = App\Models\Admin::find($workorder->offer->modified_by_id);
                      echo $admin->full_name;
                    }else if($workorder->offer->modified_by_type =="3"){
                      $vendor = App\Models\Vendor::find($workorder->offer->modified_by_id);
                      echo $vendor->full_name;
                  } else if($workorder->offer->modified_by_type =="2"){
                      $client = App\Models\Client::find($workorder->offer->modified_by_id);
                      echo $client->full_name;
                  }  ?></p>
                  </div>
                </div>
                <h3 class="flex items-center gap-2 my-4 bg-">
                  <i
                    class="fa-regular fa-message"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>
                  <span :style="{'color': 'var(--primary-color)'}"
                    >Pay Rate (For Candidate)</span
                  >
                </h3>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">pay rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->wo_pay_rate}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">over time rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->wo_over_time}}</p>
                  </div>
                </div>
                <h3 class="flex items-center gap-2 my-4 bg-">
                  <i
                    class="fa-solid fa-cash-register"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>

                  <span
                    class="capitalize"
                    :style="{'color': 'var(--primary-color)'}"
                  >
                    Bill Rate (For Vendor)</span
                  >
                </h3>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">bill rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->vendor_bill_rate}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">over time rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->vendor_overtime_rate}}</p>
                  </div>
                </div>
                <h3 class="flex items-center gap-2 my-4 bg-">
                  <i
                    class="fa-solid fa-cash-register"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>

                  <span
                    class="capitalize"
                    :style="{'color': 'var(--primary-color)'}"
                  >
                    Bill Rate (For Client)</span
                  >
                </h3>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">Bill Rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->wo_bill_rate}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">over time rate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">${{$workorder->wo_client_over_time}}</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Right Column -->
            <div
              class="w-4/6 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}"
            >
              <!-- Tabs -->
              <div
                x-data="{
                          selectedId: null,
                          init() {
                            // Set the first available tab on the page on page load.
                            this.$nextTick(() => this.select(this.$id('tab', 1)))
                          },
                          select(id) {
                            this.selectedId = id
                          },
                          isSelected(id) {
                            return this.selectedId === id
                          },
                          whichChild(el, parent) {
                            return Array.from(parent.children).indexOf(el) + 1
                          }
                          }"
                x-id="['tab']"
                class="w-full"
              >
                <!-- Tab List -->
                <ul
                  x-ref="tablist"
                  @keydown.right.prevent.stop="$focus.wrap().next()"
                  @keydown.home.prevent.stop="$focus.first()"
                  @keydown.page-up.prevent.stop="$focus.first()"
                  @keydown.left.prevent.stop="$focus.wrap().prev()"
                  @keydown.end.prevent.stop="$focus.last()"
                  @keydown.page-down.prevent.stop="$focus.last()"
                  role="tablist"
                  class="-mb-px flex items-center text-gray-500 bg-gray-100 py-1 px-1 rounded-t-lg gap-4"
                >
                  <!-- Tab -->
                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full  bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center gap-3 px-5 py-2.5 hover:rounded-lg bg-transparent capitalize"
                      role="tab"
                    >
                      <i class="fa-solid fa-circle-info"></i>
                      <span class="capitalize">workorder info</span>
                    </button>
                  </li>
                  @if($workorder->workorder_status==1)
                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center px-5 py-2.5 bg-transparent hover:rounded-lg gap-3"
                      role="tab"
                    >
                      <i class="fa-solid fa-money-bill"></i>
                      <span class="capitalize">Onboarding Info</span>
                    </button>
                  </li>
                  @endif
                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center px-5 py-2.5 bg-transparent hover:rounded-lg gap-3"
                      role="tab"
                    >
                      <i class="fa-regular fa-file"></i>
                      <span class="capitalize"
                        >Onboarding Document Background Screening</span
                      >
                    </button>
                  </li>
                </ul>

                <!-- Panels -->
                <div
                  role="tabpanels"
                  class="rounded-b-md border border-gray-200 bg-white"
                >
                  <!-- First Tab -->
                  <section
                    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                    role="tabpanel"
                    class=""
                  >
                  @php $total_cost = $workorder->job_other_amount + $workorder->single_resource_job_approved_budget; @endphp
                    <div
                      x-data="{
                            workOrderInfo: {
                                startDate: '{{ old('startDate', formatDate($workorder->start_date) ?? '') }}',
                                endDate: '{{ old('endDate', formatDate($workorder->end_date) ?? '') }}',
                                timesheetApprovingManager: '{{ old('timesheetApprovingManager', $workorder->approvalManager->full_name ?? '') }}',
                                locationOfWork: '{{ old('locationOfWork', $workorder->location->name ?? '') }}',
                                vendorName: '{{ old('vendorName',  $workorder->vendor->full_name  ?? '') }}',
                                jobType: '{{ old('jobType', $workorder->jobType->title  ?? '') }}',
                                secondaryJobTitle: '{{ old('secondaryJobTitle', $workorder->careerOpportunity->alternative_job_title ?? '') }}',
                                hiringManager: '{{ old('hiringManager', $workorder->hiringManager->full_name ?? '') }}',
                                GLAccount: '{{ old('GLAccount', $workorder->careerOpportunity->glCode->title ?? '') }}',
                                locationTax: '{{ old('locationTax', $workorder->location_tax ?? '') }}',
                                totalEstimatedCost: '{{ old('totalEstimatedCost', $total_cost ?? '') }}',
                                regularHoursEstimatedCost: '{{ old('regularHoursEstimatedCost', $workorder->single_resource_job_approved_budget ?? '') }}',
                            }
                        }"
                      class="bg-white shadow rounded-lg"
                    >
                      <div class="divide-y divide-gray-200">
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >start date:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.startDate"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >End Date:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.endDate"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Timesheet Approving Manager:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.timesheetApprovingManager"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Location of Work:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.locationOfWork"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Vendor Name:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.vendorName"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Job Type:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.jobType"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Secondary Job Title:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.secondaryJobTitle"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Hiring Manager:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.hiringManager"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >GL Account:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.GLAccount"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Location Tax (%):</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.locationTax"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Total Estimated Cost:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.totalEstimatedCost"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >Regular Hours Estimated cost:</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.regularHoursEstimatedCost"
                          ></span>
                        </div>
                      </div>
                    </div>
                  </section>
                  <!-- Second Tab-->
                  @php
                        $disabled = true;
                         if($workorder->on_board_status == 0){
                          $disabled = false;
                        }
                        @endphp
                  @if($workorder->workorder_status==1)
                  <section
                    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                    role="tabpanel"
                    class=""
                  >
                    <div
                      x-data="onBoardingData"
                      id="generalformwizard"
                      class="bg-white shadow-md rounded-lg overflow-hidden w-full"
                    >
                      <form @submit.prevent="submitForm">
                        <h3 class="mx-4 mt-4">
                          <span
                            class="text-lg capitalize"
                            :style="{'color': 'var(--primary-color)'}"
                            >Contractor Information</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Contractor Name</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="contractorName"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Personal Email Address</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="contractorEmail"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Account Manager</label
                              >
                              <select
                                class="w-full select2-single custom-style"
                                data-field="accountManagerValue"
                                id="accountManagerValue" disabled
                              >
                              @isset($workorder->vendor)
                                            <option value="{{ $workorder->vendor->id }}" 
                                               >
                                                {{ $workorder->vendor->full_name }} 
                                            </option>
                                            {{-- Team Members --}}
                                                @foreach($workorder->vendor->teamMembers as $team)
                                                <option value="{{$team->teammember_id}}" 
                                               
                                                >{{$team->teammember->full_name}}</option>
                                                @endforeach
                                            @endisset
                              </select>
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Work Location</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="workLocation"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        <h3 class="mx-4 mt-4">
                          <span
                            class="text-lg capitalize"
                            :style="{'color': 'var(--primary-color)'}"
                            >Onboarding Details</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Job Profile</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="jobProfile"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Official Email Address</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="officialEmail"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Division</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="division"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Region</label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="region"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Start Date
                                <span class="text-red-500">*</span></label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="startDate"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >End Date
                                <span class="text-red-500">*</span></label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="endDate"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Candidate Sourcing Type & Worker Type<span
                                  class="text-red-500"
                                  >*</span
                                >
                              </label>
                              <select
                                class="w-full select2-single custom-style"
                                id="candidateSourcing"
                                x-model="candidateSourcing" :disabled="isDisabled"
                              >
                              <option value="" disabled>Select</option>
                              @foreach (checksetting(18) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                              </select>
                              <p
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.candidateSourcing"
                              ></p>
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Original Start Date
                                <span class="text-red-500">*</span></label
                              >
                              <input
                                id="startDate"
                                x-model="originalStartDate"
                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                type="text"
                                required
                                placeholder="Select start date" :disabled="isDisabled"
                              />
                              <p
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.originalStartDate"
                              ></p>
                            </div>
                          </div>
                        </div>
                        <h3 class="mx-4 mt-4">
                          <span
                            class="text-lg capitalize"
                            :style="{'color': 'var(--primary-color)'}"
                            >Contractor Portal Details</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Contractor Portal ID
                                <span class="text-red-500">*</span></label
                              >
                              <input
                                type="text"
                                disabled
                                x-model="contractorPortal"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Contractor Password
                                <span class="text-red-500">*</span></label
                              >
                              <input
                                type="password"
                                disabled
                                x-model="contractorPassword"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                              />
                            </div>
                          </div>
                        </div>
                        <h3 class="mx-4 mt-4">
                          <span
                            class="text-lg capitalize"
                            :style="{'color': 'var(--primary-color)'}"
                            >Timesheet Information</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >Timesheet Type
                                <span class="text-red-500">*</span>
                              </label>
                              <select
                                class="w-full select2-single custom-style"
                                x-model="timesheetType"
                                id="timesheetType" :disabled="isDisabled"
                              >
                                <option value="" disabled>Select Timesheet Type</option>
                                @foreach (checksetting(19) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                              </select>
                              <p
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.timesheetType"
                              ></p>
                            </div>
                            <div class="flex-1"></div>
                          </div>
                        </div>
                        @if(!$disabled)
                        <div class="px-6 py-3">
                          <button
                            type="submit"
                            class="px-4 py-2 text-white capitalize rounded"
                            :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
                          >
                            Onboard
                          </button>
                        </div>
                        @endif
                      </form>
                    </div>
                  </section>
                  @endif
                  <!-- Third Tab-->
                  <section
                    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                    role="tabpanel"
                    class=""
                    >
                    @if($workorder->verification_status==1) 
                    <div
                      class="overflow-x-auto"
                      x-data="{ 
                        openModal: false, 
                        currentRowId: null,
                        rows: [
                          { id: 1, documentCheckList: 'Code of Conduct', dateTime: '10/03/2024 06:18 PM' },
                          { id: 2, documentCheckList: 'Data Privacy/Data Handling', dateTime: '10/03/2024 06:18 PM' },
                          { id: 3, documentCheckList: 'Non-Disclosure', dateTime: '10/03/2024 06:18 PM' },
                          { id: 4, documentCheckList: 'Criminal Background', dateTime: '10/03/2024 06:18 PM' },
                        ]
                      }"
                    >
                      <table class="w-full">
                        <thead>
                          <tr class="bg-gray-50 text-left">
                            <th
                              class="py-4 px-4 text-center font-semibold text-sm text-gray-600 capitalize"
                            >
                              Document Check List
                            </th>
                            <th
                              class="py-4 px-4 text-center font-semibold text-sm text-gray-600 capitalize"
                            >
                              Date & Time
                            </th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                          <template x-for="row in rows" :key="row.id">
                            <tr>
                              <td
                                class="py-4 px-4 text-center text-sm"
                                x-text="row.documentCheckList"
                              ></td>
                              <td
                                class="py-4 px-4 text-center text-sm"
                                x-text="row.dateTime"
                              ></td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                    <div class="px-6 py-3">
                      <p><b>Reviewed By:</b> System Admin</p>
                      <p><b>Reviewed Date:</b> 10/03/2024</p>
                    </div>
                    @else 
                    <p>Background verification still not submitted for review.</p>
                    @endif
                  </section>
                </div>
              </div>
            </div>
          </div>
          <!--  Business Unit -->
          <div class="mt-4">
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
            @foreach($workorder->careerOpportunity->careerOpportunitiesBu as $bu)
            <div class="flex justify-between gap-2 py-4 px-2 border-x border-b">
              <div class="w-3/5 flex-wrap">
                <span>708212 - 166 - St. Peters, MO - LPG</span>
              </div>
              <div class="w-2/5 text-center">
                <span>100%</span>
              </div>
            </div>
            @endforeach
          </div>
          <!-- Workorder History -->
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
                Workorder History
              </h2>
            </div>
            <div x-data="workOrderHistory()">
              <table
                class="min-w-full bg-white shadow-md rounded-lg overflow-hidden"
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
                    <th
                      @click="sort('status')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                      Workorder Status
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
                      Worker Type
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
                      <td class="py-3 px-4" x-text="item.id"></td>
                      <td class="py-3 px-4" x-text="item.catalogName"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td class="py-3 px-4" x-text="item.category"></td>
                      <td
                        class="py-3 px-4"
                        x-text="item.profileWorkerType"
                      ></td>
                      <td class="py-3 px-4" x-text="item.status"></td>
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
        </div>

@endsection
@php  
  if($workorder->original_start_date=='0000-00-00' || $workorder->original_start_date == '' || strtotime($workorder->original_start_date) < 0){
                $onboardOriginalStartDate = $workorder->start_date;
            }else{
                $onboardOriginalStartDate = $workorder->original_start_date;
            } 
@endphp
<script>
      document.addEventListener("alpine:init", () => {
        Alpine.data("onBoardingData", () => ({
          isDisabled: @json($disabled),
          contractorName: '{{ old('contractorName',$workorder->consultant->full_name ?? '') }}',
          contractorEmail: '{{ old('contractorEmail',$workorder->consultant->user->email ?? '') }}',
          accountManagerValue: '{{ old('accountManagerValue', $workorder->submission->emp_msp_account_mngr ?? '') }}',
          workLocation: '{{ old('workLocation',$workorder->location->name ?? '') }}',
          workLocationid: '{{ old('workLocationid',$workorder->location_id ?? '') }}',
          jobProfile: '{{ old('jobProfile',$workorder->careerOpportunity->title ?? '') }}',
          officialEmail: '{{ old('officialEmail',$workorder->consultant->user->email ?? '') }}',
          division: '{{ old('division',$workorder->careerOpportunity->division->name ?? '') }}',
          region: '{{ old('region',$workorder->careerOpportunity->regionZone->name ?? '') }}',
          startDate: '{{ old('startDate',formatDate($workorder->start_date) ?? '') }}',
          endDate: '{{ old('endDate',formatDate($workorder->end_date) ?? '') }}',
          contractorPortal: '{{ old('contractorPortal',$workorder->consultant->candidate_id ?? '') }}',
          contractorPassword: '{{ old('contractorPassword',$workorder->consultant->user->password ?? '') }}',
          workorder_id: '{{ old('workorder_id',$workorder->id ?? '') }}',
          // New fields for user input
          candidateSourcing:'{{ old('candidateSourcing',$workorder->sourcing_type ?? '') }}',
          originalStartDate: '{{ old('originalStartDate',formatDate($onboardOriginalStartDate) ?? '') }}',
          timesheetType: '{{ old('timesheetType',$workorder->contract->type_of_timesheet ?? '') }}',

          // Error messages
          errors: {
            candidateSourcing: "",
            originalStartDate: "",
            timesheetType: "",
          },

          init() {
            this.initSelect2();
            this.initDatePickers();
          },

          initSelect2() {
            this.$nextTick(() => {
              $(".select2-single").each((index, element) => {
                const fieldName = $(element).data("field");
                const $select = $(element);

                if (fieldName === "accountManager") {
                  $select.select2({
                    width: "100%",
                    disabled: this.accountManagerValue !== "",
                  });

                  if (this.accountManagerValue) {
                    $select.val(this.accountManagerValue).trigger("change");
                  }
                } else {
                  $select.select2({
                    width: "100%",
                  });
                }
              });

              // Initialize Select2 for new dropdowns
              $("#candidateSourcing, #timesheetType")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this[e.target.id] = e.target.value;
                  // Clear error message when a value is selected
                  this.errors[e.target.id] = "";
                });
            });
          },

          initDatePickers() {
            this.$nextTick(() => {
              flatpickr("#startDate", {
                dateFormat: "m/d/Y",
                onChange: (selectedDates, dateStr) => {
                  this.originalStartDate = dateStr;
                  this.errors.originalStartDate = "";
                },
              });
            });
          },

          validateForm() {
            let isValid = true;
            this.errors = {
              candidateSourcing: "",
              originalStartDate: "",
              timesheetType: "",
            };

            if (!this.candidateSourcing) {
              this.errors.candidateSourcing =
                "Please select a candidate sourcing type";
              isValid = false;
            }

            if (!this.originalStartDate) {
              this.errors.originalStartDate =
                "Please select an original start date";
              isValid = false;
            }

            if (!this.timesheetType) {
              this.errors.timesheetType = "Please select a timesheet type";
              isValid = false;
            }

            return isValid;
          },

          submitForm() {
            if (this.validateForm()) {
              // Collect all form data, including disabled fields
              const formRecord = {
                contractorName: this.contractorName,
                contractorEmail: this.contractorEmail,
                accountManagerValue: this.accountManagerValue,
                workLocation: this.workLocation,
                jobProfile: this.jobProfile,
                officialEmail: this.officialEmail,
                division: this.division,
                region: this.region,
                startDate: this.startDate,
                endDate: this.endDate,
                contractorPortal: this.contractorPortal,
                contractorPassword: this.contractorPassword,
                candidateSourcing: this.candidateSourcing,
                originalStartDate: this.originalStartDate,
                timesheetType: this.timesheetType,
                workorder_id:this.workorder_id,
                workLocationid:this.workLocationid,
              };

              console.log("Form submitted with data:", formRecord);
              let formData = new FormData();
              Object.keys(formRecord).forEach((key) => {
                  if (Array.isArray(formRecord[key])) {
                      // If the key is an array (like businessUnits), handle each item
                      formRecord[key].forEach((item, index) => {
                          formData.append(`${key}[${index}]`, JSON.stringify(item));
                      });
                  } else {
                      formData.append(key, formRecord[key]);
                  }
              });
                const url = "/admin/contracts";
              ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);

              // Add your API call here to submit the data
              // For example:
              // fetch('/api/onboard', {
              //   method: 'POST',
              //   headers: {
              //     'Content-Type': 'application/json',
              //   },
              //   body: JSON.stringify(formData),
              // })
              // .then(response => response.json())
              // .then(data => {
              //   console.log('Success:', data);
              // })
              // .catch((error) => {
              //   console.error('Error:', error);
              // });
            } else {
              console.log("Form validation failed");
            }
          },
        }));
      });

      function workOrderHistory() {
        return {
          items: Array.from({ length: 200 }, (_, i) => ({
            id: i + 1,
            catalogName: `Catalog ${String.fromCharCode(65 + (i % 26))}`,
            category: ["Electronics", "Books", "Clothing", "Home", "Sports"][
              i % 5
            ],
            profileWorkerType: ["Full-time", "Part-time", "Contract"][i % 3],
            status: i % 2 === 0 ? "Active" : "Inactive",
          })),
          sortColumn: "id",
          sortDirection: "asc",
          itemsPerPage: 10,
          customItemsPerPage: 10,
          currentPage: 1,
          itemsPerPageControl() {
            return `
                          <div class="flex items-center gap-4">
                              <label for="itemsPerPage" class="mr-2">Items per page:</label>
                              <select id="itemsPerPage" x-model="itemsPerPage" @change="updatePagination()" class="border rounded px-2 py-1 mr-2">
                                  <option>10</option>
                                  <option>20</option>
                                  <option>30</option>
                                  <option>40</option>
                                  <option>50</option>
                                  <option>100</option>
                                  <option value="custom">Custom</option>
                              </select>
                              <div x-show="itemsPerPage === 'custom'" class="flex items-center">
                                  <input 
                                      type="number" 
                                      x-model.number="customItemsPerPage" 
                                      @input="updateCustomPagination()"
                                      min="1"
                                      class="border rounded px-2 py-1 w-20"
                                      placeholder="Enter"
                                  >
                              </div>
                          <div>
                      `;
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
          getItemsPerPage() {
            return this.itemsPerPage === "custom"
              ? this.customItemsPerPage
              : parseInt(this.itemsPerPage);
          },
          get sortedItems() {
            return [...this.items].sort((a, b) => {
              let modifier = this.sortDirection === "asc" ? 1 : -1;
              if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier;
              if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier;
              return 0;
            });
          },
          get paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.getItemsPerPage();
            return this.sortedItems.slice(
              startIndex,
              startIndex + this.getItemsPerPage()
            );
          },
          get totalPages() {
            return Math.ceil(this.items.length / this.getItemsPerPage());
          },
          get visiblePageNumbers() {
            const totalPageCount = this.totalPages;
            const current = this.currentPage;
            let start = Math.max(1, current - 3);
            let end = Math.min(totalPageCount, start + 7);

            if (end - start < 7) {
              start = Math.max(1, end - 7);
            }

            return Array.from({ length: end - start + 1 }, (_, i) => start + i);
          },
          get showBackwardIcon() {
            return this.currentPage > 4;
          },

          get showForwardIcon() {
            return this.currentPage < this.totalPages - 3;
          },

          movePages(direction) {
            const newPage = this.currentPage + direction * 1;
            this.goToPage(Math.max(1, Math.min(newPage, this.totalPages)));
          },
          prevPage() {
            if (this.currentPage > 1) {
              this.currentPage--;
            }
          },
          nextPage() {
            if (this.currentPage < this.totalPages) {
              this.currentPage++;
            }
          },
          goToPage(page) {
            this.currentPage = page;
          },
          deleteItem(id) {
            this.items = this.items.filter((item) => item.id !== id);
            this.updatePagination();
          },
          updatePagination() {
            this.currentPage = 1;
          },
          updateCustomPagination() {
            if (this.customItemsPerPage < 1) {
              this.customItemsPerPage = 1;
            }
          },
          applyCustomPagination() {
            this.itemsPerPage = "custom";
            this.updatePagination();
          },
        };
      }
    </script>
