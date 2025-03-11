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
                  {{translate('My Web Application - Workorder ID:')}} {{$workorder->id}}
              </h3>
            </div>
            <div class="flex items-center gap-2">
              @if($workorder->status ==1 && $workorder->on_board_status==0)
                <div x-data="{     //withdraw workorder/submission
        rejectModal1: false,
        workorderId: '{{ $workorder->id }}',
        reason: '',
        note: '',
        errors: {},
        validateForm() {
            this.errors = {};
            if (!this.reason.trim()) this.errors.reason = 'Please select a reason';
            return Object.keys(this.errors).length === 0;
        },
        submitForm() {
            if (this.validateForm()) {
                let formData = new FormData();
                formData.append('note', this.note);
                formData.append('workorder_id', this.workorderId);
                formData.append('reason', this.reason);
                const url = '/admin/workorder/withdrawWorkorder';
                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);

                this.rejectModal1 = false;
            }
        },
        clearError(field) {
            delete this.errors[field];
        }
    }">
                    <button
                        @click="rejectModal1 = true"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize"
                    >{{translate('Withdraw Workorder/Submission')}}</button>
                    <div
                        x-show="rejectModal1"
                        @click.away="rejectModal1 = false"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white" @click.stop>
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between border-b p-4">
                                <h2 class="text-xl font-semibold">{{translate('Withdraw Submission')}}</h2>
                                <button
                                    @click="rejectModal1 = false"
                                    class="text-gray-400 hover:text-gray-600 bg-transparent hover:bg-transparent"
                                >
                                    &times;
                                </button>
                            </div>
                            <!-- Modal Body -->
                            <div class="p-4">
                                <form @submit.prevent="submitForm" id="generalformwizard">
                                    @csrf
                                    <input type="hidden" name="workorder_id" id="workorder_id" x-model="workorder_id" :value="workorderId">

                                    <!-- Reason for Withdrawal -->
                                    <div class="mb-4">
                                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                                            Reason for Withdrawal
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="reason"
                                            x-model="reason"
                                            @change="clearError('reason')"
                                            :class="{'border-red-500': errors.reason}"
                                            class="w-full border rounded-md shadow-sm"
                                        >
                                            <option value="">Select</option>
                                            @foreach($rejectReasons as $reason)
                                                <option value="{{ $reason->id }}">{{ $reason->title }}</option>
                                            @endforeach
                                        </select>
                                        <p
                                            x-show="errors.reason"
                                            x-text="errors.reason"
                                            class="text-red-500 text-xs mt-1"
                                        ></p>
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                                            Note <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            id="note"
                                            rows="4"
                                            class="w-full border border-gray-300 rounded-md shadow-sm"
                                            x-model="note"
                                        ></textarea>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex justify-end space-x-2 border-t p-4">
                                <button
                                    type="button"
                                    @click="rejectModal1 = false"
                                    class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                                >
                                    Close
                                </button>
                                <button
                                    type="button"
                                    @click="submitForm"
                                    class="rounded-md bg-green-500 px-4 py-2 text-sm font-medium text-white hover:bg-green-600"
                                >
                                    Withdraw
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

              <a href="{{ route('admin.workorder.index') }}">
                  <button
                      type="button"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                  >
                      {{translate('Back to List of Workorders')}}
                  </button>
              </a>

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
                  >{{translate('Work order information')}}</span
                >
              </h3>
              <div class="flex flex-col">
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">{{translate('Contractor name:')}}</h4>
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
                    <h4 class="font-medium capitalize">{{translate('Offer ID:')}}</h4>
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
                    <h4 class="font-medium capitalize">{{translate('Unique ID:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{$workorder->consultant->unique_id}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Workorder Status:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">
                      <span
                        class="px-2 inline-flex text-xs leading-5 text-white font-semibold rounded-full bg-green-500"
                        >{{\App\Models\CareerOpportunitiesWorkorder::getWorkorderStatus($workorder->status)}}</span
                      >
                    </p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">{{translate('Offer accepted date:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{formatDate($workorder->offer->offer_accept_date)}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium capitalize">{{translate('Offer accepted by:')}}</h4>
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
                @if($job->workerType->id == 10)
                  <h3 class="flex items-center gap-2 my-4 bg-">
                    <i
                      class="fa-regular fa-message"
                      :style="{'color': 'var(--primary-color)'}"
                    ></i>
                    <span :style="{'color': 'var(--primary-color)'}"
                      >{{translate('Pay Rate (For Candidate)')}}</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Pay rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$workorder->wo_pay_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Over time rate:')}}</h4>
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
                      {{translate('Bill Rate (For Vendor)')}}</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Bill rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$workorder->vendor_bill_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Over time rate:')}}</h4>
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
                      {{translate('Bill Rate (For Client)')}}</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Bill Rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$workorder->wo_bill_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium capitalize">{{translate('Over time rate:')}}</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$workorder->wo_client_over_time}}</p>
                    </div>
                  </div>
                @endif
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
                      <span class="capitalize">{{translate('Workorder info')}}</span>
                    </button>
                  </li>
                  @if($workorder->status==1 && $job->workerType->id == 10)
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
                      <span class="capitalize">{{translate('Onboarding Info')}}</span>
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
                        >{{translate('Onboarding Document Background Screening')}}</span
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
                            >{{translate('start date:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.startDate"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('End Date:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.endDate"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Timesheet Approving Manager:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.timesheetApprovingManager"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Location of Work:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.locationOfWork"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Vendor Name:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.vendorName"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Job Type:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.jobType"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Secondary Job Title:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.secondaryJobTitle"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Hiring Manager:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.hiringManager"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('GL Account:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.GLAccount"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Location Tax (%):')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.locationTax"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Total Estimated Cost:')}}</span
                          >
                          <span
                            class="font-semibold"
                            x-text="workOrderInfo.totalEstimatedCost"
                          ></span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600 capitalize"
                            >{{translate('Regular Hours Estimated cost:')}}</span
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
                  @if($workorder->status==1)
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
                            >{{translate('Contractor Information')}}</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >{{translate('Contractor Name')}}</label
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
                                >{{translate('Personal Email Address')}}</label
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
                                >{{translate('Account Manager')}}</label
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
                                >{{translate('Work Location')}}</label
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
                            >{{translate('Onboarding Details')}}</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >{{translate('Job Profile')}}</label
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
                                >{{translate('Official Email Address')}}</label
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
                                >{{translate('Division')}}</label
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
                                >{{translate('Region')}}</label
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
                                >{{translate('Start Date')}}
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
                                >{{translate('End Date')}}
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
                                >{{translate('Candidate Sourcing Type & Worker Type')}}<span
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
                                >{{translate('Original Start Date')}}
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
                            >{{translate('Contractor Portal Details')}}</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >{{translate('Contractor Portal ID')}}
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
                                >{{translate('Contractor Password')}}
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
                            >{{translate('Timesheet Information')}}</span
                          >
                        </h3>
                        <div class="px-6 py-3">
                          <div class="flex space-x-4">
                            <div class="flex-1">
                              <label class="block mb-2 capitalize"
                                >{{translate('Timesheet Type')}}
                                <span class="text-red-500">*</span>
                              </label>
                              <select
                                class="w-full select2-single custom-style"
                                x-model="timesheetType"
                                id="timesheetType" :disabled="isDisabled"
                              >
                                <option value="" disabled>{{translate('Select Timesheet Type')}}</option>
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
                              {{translate('Onboard')}}
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
                                  {{translate('Document Check List')}}
                            </th>
                            <th
                              class="py-4 px-4 text-center font-semibold text-sm text-gray-600 capitalize"
                            >
                                {{translate('Date & Time')}}
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
                      <p><b>{{translate('Reviewed By:')}}</b> System Admin</p>
                      <p><b>{{translate('Reviewed Date:')}}</b> 10/03/2024</p>
                    </div>
                    @else
                    <p>{{translate('Background verification still not submitted for review.')}}</p>
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
                <span class="text-white">{{translate('Business Unit')}}</span>
              </div>
              <div class="w-2/5 text-center">
                <span class="text-white">{{translate('Budget Percentage')}}</span>
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
                  {{translate('Workorder History')}}
                </h2>
              </div>
              <div x-data="workOrderHistory()" style="overflow: scroll;">
                <table
                  class="min-w-full bg-white shadow-md rounded-lg overflow-hidden" style="width: max-content;"
                >
                  <thead class="bg-gray-200 text-gray-700">
                    <tr>
                      <th class="py-3 px-4 text-left">{{translate('Status')}}</th>
                    <th
                      @click="sort('workOrID')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Workorder ID')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'workOrID' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'workOrID' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('candidate')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Contractor Name')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'candidate' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'candidate' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('jobID')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Job ID')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'jobID' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'jobID' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('hiringManager')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Hiring Manager')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'hiringManager' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'hiringManager' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('vendor')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Vendor')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'vendor' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'vendor' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('date')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Start Date')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'date' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'date' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('endDate')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('End Date')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'endDate' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'endDate' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th
                      @click="sort('billRate')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Bill Rate')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'billRate' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'billRate' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>

                    <th
                      @click="sort('jobType')"
                      class="py-3 px-4 text-left cursor-pointer"
                    >
                        {{translate('Worker Type')}}
                      <span class="ml-1">
                        <i
                          class="fas fa-sort-up"
                          :class="{'text-blue-500': sortColumn === 'jobType' && sortDirection === 'asc'}"
                        ></i>
                        <i
                          class="fas fa-sort-down"
                          :class="{'text-blue-500': sortColumn === 'jobType' && sortDirection === 'desc'}"
                        ></i>
                      </span>
                    </th>
                    <th class="py-3 px-4 text-left">{{translate('Action')}}</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="item in paginatedItems" :key="item.id">
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                          <td class="py-3 px-4" x-text="item.status"></td>
                          <td class="py-3 px-4" x-text="item.workOrID"></td>
                          <td class="py-3 px-4" x-text="item.candidate"></td>
                          <td class="py-3 px-4" x-text="item.jobID"></td>
                          <td class="py-3 px-4" x-text="item.hiringManager"></td>
                          <td class="py-3 px-4" x-text="item.vendor"></td>
                          <td class="py-3 px-4" x-text="item.date"></td>
                          <td class="py-3 px-4" x-text="item.endDate"></td>
                          <td class="py-3 px-4" x-text="item.billRate"></td>
                        <td class="py-3 px-4" x-text="item.jobType"></td>
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
        const logs = @json($logs);
        return {
          items: logs.map(log => ({
                id: log.id,  // Log ID
                status: log.properties.attributes.status_name,
                workOrID: log.properties.attributes.id,
                candidate: log.properties.attributes.candidate_name,
                jobID: log.properties.attributes.career_opportunity_id,
                hiringManager: log.properties.attributes.hiring_manager_name,
                vendor: log.properties.attributes.vendor_name,
                date: log.properties.attributes.start_date_formatted,
                endDate: log.properties.attributes.end_date_formatted,
                billRate: log.properties.attributes.wo_bill_rate,
                jobType: log.properties.attributes.job_type_title,
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

            deleteItem(id) {
            //  alert(id);
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
        };
      }
    </script>
