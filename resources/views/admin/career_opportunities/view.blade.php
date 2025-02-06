@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          @include('admin.layouts.partials.header')
          <div  x-data="{ tab: 'activejobs',jobDetails: null, submissionDetails: null }" @job-details-updated.window="jobDetails = $event.detail"
          @submission-details-updated.window="submissionDetails = $event.detail" class="bg-white mx-4 my-8 rounded p-8">
              @include('admin.layouts.partials.alerts')

          @if($job->jobStatus == 5)
              <div x-data="{
                    rejectionReason: '{{ $job->rejectionReason ? $job->rejectionReason->title : ' ' }}',
                    notes: '{{ $job->note_for_rejection }}',
                    rejectedBy: '{{ $job->rejectionUser ? $job->rejectionUser->name : '' }}',
                    rejectionDate: '{{ $job->date_rejected }}'

                }"
                >
                    <div class="alert alert-danger">
                        <span class="bold">Rejection Reason:</span> <span x-text="rejectionReason"></span><br>
                        <span class="bold">Notes:</span> <span x-text="notes"></span><br>
                        <span class="bold">Rejected By:</span> <span x-text="rejectedBy"></span><br>
                        <span class="bold">Rejection Date:</span> <span x-text="rejectionDate"></span>
                    </div>
                </div>
              @endif
              <x-job-details />

                <x-submission-details />

          <div class="mb-4">
            <ul
              class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1 -mx-6"
            >
              <li class="flex justify-center">
                <a
                @click="tab = 'activejobs'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'activejobs' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Job Details')}}</span>

                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'submission'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'submission' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Job Submission')}} </span>
                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'ranking'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'ranking' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Ranking')}}</span>

                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'interview'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'interview' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Interviews')}}</span>

                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'offer'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'offer' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Offers')}}</span>

                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'workorder'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'workorder' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Workorders')}}</span>

                </a>
              </li>
              <li class="flex justify-center">
                <a
                @click="tab = 'notes'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'notes' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Add notes')}}</span>

                </a>
              </li>

              <li class="flex justify-center">
                <a
                @click="tab = 'jobworkflow'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'jobworkflow' }"
                class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-fill"></i>
                  <span class="capitalize">{{translate('Workflow')}}</span>

                </a>
              </li>
               <li class="flex justify-center" x-data="{ status: {{ $job->jobStatus }} }" x-show="status === 3 || status === 11 || status === 13">
                <a
                @click="tab = 'vendorrelease'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'vendorrelease' }"
                class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                    <i class="fa-solid fa-fill"></i>
                    <span class="capitalize">{{translate('Vendor Release')}}</span>
                    <div
                      class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                    >
                        <span class="text-[10px]">20</span>
                    </div>
                </a>
            </li>

            <li class="flex justify-center">
                <a
                @click="tab = 'jobteammember'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'jobteammember' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">{{translate('Job Team Member')}}</span>

                </a>
              </li>

            <li class="flex justify-center">
              <a
                @click="tab = 'pmoteammember'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'pmoteammember' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
              >
                <i class="fa-regular fa-file-lines"></i>
                <span class="capitalize">{{translate('PMO Specialist')}}</span>
              </a>
            </li>
            <li class="flex justify-center">
              <a
                @click="tab = 'history'"
                :class="{ 'border-blue-500 text-blue-500': tab === 'history' }"
                class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
              >
                <i class="fa-regular fa-file-lines"></i>
                <span class="capitalize">{{translate('history')}}</span>
              </a>
            </li>






              <!-- <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-briefcase"></i>
                  <span class="capitalize">all jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">4320</span>
                  </div>
                </a>
              </li> -->
            </ul>
          </div>
        <div x-show="tab === 'activejobs'" class="flex w-full gap-4">


          <div class="bg-white mx-4 my-8 rounded p-8 mt-0 pt-0">
              <div class="flex justify-between items-center mb-6">
                  <h2 class="text-2xl font-bold"></h2>

                  <div class="flex space-x-2">
                    <div class="flex space-x-2" x-data="{
                        rejectModal1: false,
                        jobId: '{{ $job->id }}',
                        status: {{ $job->jobStatus }},
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
                                formData.append('job_id', this.jobId);
                                formData.append('reason', this.reason);
                                const url = '/admin/rejectAdminJob';
                                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                                this.rejectModal1 = false;
                            }
                        },
                        clearError(field) {
                            delete this.errors[field];
                        }
                      }">
                        <!-- Trigger Button -->
                        <button
                            x-show="status == 22"
                            @click="rejectModal1 = true; currentRowId = '{{ $job->id }}'"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 capitalize">
                            Reject
                        </button>

                        <!-- Modal -->
                        <div
                            x-show="rejectModal1"
                            @click.away="rejectModal1 = false"
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
                                    <h2 class="text-xl font-semibold">Reject Workflow</h2>
                                    <button
                                        @click="rejectModal1 = false"
                                        class="text-gray-400 hover:text-gray-600 bg-transparent hover:bg-transparent"
                                    >
                                        &times;
                                    </button>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <form @submit.prevent="submitForm" id="generalformwizard">
                                        @csrf
                                        <input type="hidden" name="workflow_id" id="workflow_id" x-model="workflow_id" :value="{{ $job->id }}">
                                        <div class="mb-4">
                                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                                                Reason for Rejection
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

                                <!-- Footer -->
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
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>




                      <div x-data="{
                            jobId: '{{ $job->id }}',
                            status: {{ $job->jobStatus }},
                            submitApprove() {
                                let formData = new FormData();
                                const url = '{{ route('admin.jobApprove', $job->id) }}';
                                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                            }
                          }">
                          <button
                              type="button"
                              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                              x-show="status == 22"
                              @click="submitApprove"
                          >
                              Approve
                          </button>
                      </div>
                    </div>

                      <form action="{{ route('admin.career-opportunities.copy', $job->id) }}" method="POST" style="display: inline-block;">
                          @csrf
                          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize">
                              {{translate('Copy Career Opportunity')}}<i class="fas fa-copy"></i>
                          </button>
                      </form>
                  </div>
              </div>
          <div class="flex w-full gap-4">

            <!-- Left Column -->
            <div
              class="w-1/3 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}">
              <!-- Cards -->
              <div>
                <div class="flex gap-4 w-full">
                  <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                  >
                    <div class="flex flex-col gap-2 items-center">
                      <div
                        class="bg-[#ddf6e8] w-8 h-8 rounded-full flex items-center justify-center"
                      >
                        <i class="fa-solid fa-dollar-sign text-[#28c76f]"></i>
                      </div>
                      <div class="text-center">
                        <span
                          class="font-bold text-sm font-normal text-[#28c76f]"
                          >{{translate('Regular Hours Cost')}}</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                      <span>${{ $job->regular_hours_cost }}</span>
                    </div>
                  </div>
                  <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                  >
                    <div class="flex flex-col gap-2 items-center">
                      <div
                        class="bg-[#D6F4F8] w-8 h-8 rounded-full flex items-center justify-center"
                      >
                        <i class="fa-solid fa-dollar-sign text-[#00bad1]"></i>
                      </div>
                      <div class="text-center">
                        <span
                          class="font-bold text-sm font-normal text-[#00bad1]"
                          >{{translate('Single Resource Cost')}}</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                        <span>${{ $job->single_resource_total_cost }}</span>
                    </div>
                  </div>
                  <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                  >
                    <div class="flex flex-col gap-2 items-center">
                      <div
                        class="bg-[#FFF0E1] w-8 h-8 rounded-full flex items-center justify-center"
                      >
                        <i class="fa-solid fa-dollar-sign text-[#ff9f43]"></i>
                      </div>
                      <div class="text-center">
                        <span
                          class="font-bold text-sm font-normal text-[#ff9f43]"
                          >{{translate('All Resources Cost')}}</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                        <span>${{ $job->all_resources_total_cost }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Business Unit & Business Percentage -->
              <div class="mt-4">
                <div
                  class="flex py-4 px-2 rounded rounded-b-none"
                  :style="{'background-color': 'var(--primary-color)'}"
                >
                  <div class="w-3/5">
                    <span class="text-white">{{translate('Business Unit')}}</span>
                  </div>
                  <div class="w-2/5 text-center">
                      <p class="font-light">%</p>
                  </div>
                </div>
                  @foreach($job->careerOpportunitiesBu as $bu)
                      <div
                          class="flex justify-between gap-2 py-4 px-2 border-x border-b"
                      >
                          <div class="w-3/5 flex-wrap">
                              <span><p class="font-light">{{ $bu->buName->name ?? 'N/A' }}</p></span>
                          </div>
                          <div class="w-2/5 text-center">
                              <span>{{ $bu->percentage ?? 'N/A' }}%</span>
                          </div>
                      </div>
                  @endforeach
              </div>
              {{--<div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">
                  Please list the preferred agency(s)/vendor(s) to utilize for
                  filling this position, and list any other relevant information
                  for the Program Office
                </p>
                <div class="mt-4">
                  <ul class="color-[#202124] font-light">
                    <li>PRG</li>
                    <li>Canon</li>
                    <li>Insight Global</li>
                    <li>Professional Staffing</li>
                  </ul>
                </div>
                <p class="mt-4">UPDATE HIRING MANAGER TO Suzanne Touch</p>
                <p class="mt-4">(Justin Stephenson Vacancy)</p>
              </div>--}}
              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">{{translate('Internal Notes')}}</p>
                <div class="mt-4">
                  <ul class="color-[#202124] font-light">
                    {!! $job->internal_notes !!}
                  </ul>
                </div>
              </div>
                <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                    <p class="color-[#202124] font-light">{{translate('Skills')}}</p>
                    <div class="mt-4">
                        <ul class="color-[#202124] font-light">
                            {!! $job->skills !!}
                        </ul>
                    </div>
                </div>
              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">
                    {{translate('Pre-Identified Candidate')}}
                  </p>
                  <div
                    class="flex items-center mt-4 border rounded"
                    :style="{'border-color': 'var(--primary-color)'}"
                  >
                    <div
                      class="py-4 w-2/4 pl-4 rounded rounded-r-none"
                      :style="{'background-color': 'var(--primary-color)'}"
                    >
                      <span class="text-white font-light"
                        >{{translate('Pre-Identified Candidate?')}}</span
                      >
                    </div>
                    <div class="w-2/4 pl-4">
                      <span class="color-[#202124] font-light">{{$job->pre_candidate}}</span>
                  </div>
                </div>
                @if($job->pre_candidate == 'Yes')

                <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Candidate First Name:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->pre_name}}</p>
                      </div>
                  </div>
                <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Candidate middle Name:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->pre_middle_name}}</p>
                      </div>
                  </div>
                <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Candidate Last Name:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->pre_last_name}}</p>
                      </div>
                  </div>

                <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Candidate Phone:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->candidate_phone}}</p>
                      </div>
                  </div>

                <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Candidate Email:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->candidate_email}}</p>
                      </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{translate('Worker Pay Rate:')}}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{$job->pre_current_rate}}</p>
                      </div>
                  </div>
                @endif
              </div>
            </div>
            <!-- Middle Column -->
            <div
              class="w-1/3 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}"
            >
              <h3 class="flex items-center gap-2 mb-4 bg-">
                <i
                  class="fa-solid fa-inbox"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}"
                  >{{translate('Job Info')}}</span
                >
              </h3>
              <div class="flex flex-col">
                  <div class="flex items-center justify-between py-4 border-t">
                      <div class="w-2/4">
                          <h4 class="font-medium">{{ translate('Job Status:') }}</h4>
                      </div>
                      <div class="w-2/4">
                          <p class="font-light">{{ \App\Models\CareerOpportunity::getStatus($job->jobStatus) }}</p>
                      </div>
                  </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Job Title:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->title }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Hiring Manager:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->hiringManager->full_name ?? '' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Job Title for Email Signature:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{$job->alternative_job_title}}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Work Location:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">
                        {{ locationName($job->location_id)}}
                    </p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Division:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->division->type ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Region/Zone:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->regionZone->type ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Branch:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->branch->type ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Job Code:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->job_code ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Category:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->category->title ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Travel Required:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->travel_required ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Business Reason:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->businessReason->title ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Time System:')}}</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">{{ $job->jobType->title ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Client Billable:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->client_billable ?? 'N/A' }}</p>
                  </div>
                </div>
                @if($job->client_billable == 'Yes')
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Client Name:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->client_name ?? 'N/A' }}</p>
                  </div>
                </div>
                @endif
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Expenses Allowed?')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->expenses_allowed ?? 'N/A' }}</p>
                  </div>
                </div>
                @if($job->expenses_allowed == 'Yes')
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Estimated Expense')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->expense_cost ?? 'N/A' }}</p>
                  </div>
                </div>
                @endif
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Remote Candidate:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->remote_option ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Number of Opening(s):')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->num_openings ?? 'N/A' }}</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Worker Type:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->workerType->title ?? 'N/A' }}</p>
                  </div>
                </div>
            {{--<div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Job Family:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">777007 - Contingent Worker-Claims</p>
                  </div>
                </div>--}}
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">{{translate('GL Account:')}}</h4>
                  </div>
                  <div class="w-2/4">
                      <p class="font-light">{{ $job->glCode->title ?? 'N/A' }}</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Right Column -->
            <div
              class="w-1/3 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}"
            >
              <h3 class="flex items-center gap-2 mb-4">
                <i
                  class="fa-regular fa-clock"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}"
                  >{{translate('Job Duration')}}</span
                >
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Work Days / Week:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->day_per_week ?? 'N/A' }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Total Hours/Week:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->hours_per_week ?? 'N/A' }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Estimated Hours / Day:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->hours_per_day ?? 'N/A' }}</p>
                </div>
              </div>
            {{-- <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Total Time:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">1040</p>
                </div>
              </div>--}}
              <div class="flex items-center justify-between py-4 border-y">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Job Duration:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->date_range }} </p>
                </div>
              </div>
              <!-- Rates -->
              <h3 class="flex items-center gap-2 my-4">
                <i
                  class="fa-regular fa-money-bill-1"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}">{{translate('Rates')}}</span>
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Unit of Measure:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->paymentType->title }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Currency:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->currency->symbol->title ?? 'N/A' }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Minimum Bill Rate:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->min_bill_rate }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Maximum Bill Rate:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->max_bill_rate }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-y">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Time Type:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->jobType->title ?? 'N/A' }}</p>
                </div>
              </div>
              <!-- Job Publish Info -->
              <h3 class="flex items-center gap-2 my-4">
                <i
                  class="fa-solid fa-upload"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}"
                  >{{translate('Job Publish Info')}}</span
                >
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Job Created at:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->created_at ?? 'N/A' }}</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">{{translate('Job Created By:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $job->createdBy->name ?? 'N/A' }}</p>
                </div>
              </div>
              @if(!empty($job->job_details))
                @php
                  $jobDetails = json_decode($job->job_details, true); // Decode JSON into an array
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

                @foreach ($jobDetails as $key => $value)
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">{{ $key }}:</h4>
                    </div>
                    <div class="w-2/4">
                      @if(is_array($value))
                          <p class="font-light">{{ implode(', ', $value) }}</p>
                      @else
                          <p class="font-light">{{ $value }}</p>
                      @endif
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
      <div x-show="tab === 'submission'">
          @include('admin.career_opportunities.submission')
      </div>

      <div x-show="tab === 'ranking'">
          @include('admin.career_opportunities.ranking')
      </div>

      <div x-show="tab === 'jobteammember'">
          @include('admin.career_opportunities.jobteammember')
      </div>

      <div x-show="tab === 'pmoteammember'">
          @include('admin.career_opportunities.pmoteammember')
      </div>

      <div x-show="tab === 'interview'">
          @include('admin.career_opportunities.interview')
      </div>

      <div x-show="tab === 'offer'">
          @include('admin.career_opportunities.offer')
      </div>

      <div x-show="tab === 'workorder'">
          @include('admin.career_opportunities.workorder')
      </div>
      <div x-show="tab === 'history'">
          @include('admin.career_opportunities.history')
      </div>
      <div x-show="tab === 'notes'" x-data="{
      note: '',
      jobId: '{{ $job->id }}',
      submitForm() {
          console.log('Submitting form with note:', this.note);
          let formData = new FormData();
          formData.append('note', this.note);
          formData.append('job_id', this.jobId);
          const url = '{{ route('admin.saveNotes') }}';
          // Make sure ajaxCall is defined
          ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
      }
  }">
      <div class="row padding_cls">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show_hd_message" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <strong>Message!</strong> <span class="insert_message"></span>.
            </div>

            <div id="notesmessagewarning"></div>

            <div class="col-12">
                <div class="interview-notes-comments p-t-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="media">
                                <form @submit.prevent="submitForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="media-body">
                                        <label for="comment">{{translate('Add Notes')}}</label>
                                        <input type="hidden" id="hidden_job_id" value="{{ $job->id }}">
                                        <textarea id="comment" x-model="note" required class="form-control " placeholder="Enter text ..." style="width: 100%; min-height: 100px"></textarea>
                                        <button type="submit" class="btn btn-success mt-3">{{translate('Submit')}}</button>
                                        <button type="button" class="btn btn-success wait_comment mt-3" style="display: none;">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @foreach ($job->jobNotes as $note)
                                <div class="uiv2-note-wrapper my-3">
                                    <div class="dialogbox">
                                        <div class="body">
                                            <span class="tip tip-up"></span>
                                            <div class="message break-all">
                                                <p><strong>{{ $note->notes }}</strong></p>
                                            </div>
                                            <p class="postedby meta-inner pull-left">
                                                Posted By: {{ $note->posted_by_type }}
                                            </p>
                                            <p class="postedby meta-inner pull-left">
                                                Name: {{ Auth::user()->name }}
                                            </p>
                                            <p class="meta-inner pull-left" style="color: #8b92ca;">
                                                {{ formatDateTime($note->created_at)}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      </div>


      <div x-show="tab === 'jobworkflow'"   class="flex w-full gap-4">
         <div
          class="w-100 p-[30px] rounded border"
          :style="{'border-color': 'var(--primary-color)'}">
          <table class="min-w-full divide-y divide-gray-200" id="example">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Sr. #')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Approver Name')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Approval Type')}}
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Approval Required')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate(' Approval/Rejected By')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Release Date & Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Approved/Rejected Date & Time')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Approval Notes')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{translate('Approval Document')}}
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  {{translate('Action')}}
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach($jobWorkFlow as $index => $workflow)
              <tr>
                <td class="py-4 px-4 text-center text-sm">{{ $index + 1 }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ $workflow->hiringManager->full_name  }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ isset($workflow->approval_role_id) && isset(userRoles()[$workflow->approval_role_id]) ? userRoles()[$workflow->approval_role_id] : 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ $workflow->approval_required ?? 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ isset($workflow->approve_reject_by) ? ($workflow->approveRejectBy->name ?? 'N/A') : 'N/A' }}</td>

                <td class="py-4 px-4 text-center text-sm">{{ isset($workflow->created_at) ? formatDateTime($workflow->created_at) : 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ formatDateTime($workflow->approved_datetime) ?? 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ $workflow->approval_notes ?? 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm"><div x-data="{
                        downloadUrl: '',
                        id: '{{ $workflow->id }}',
                        fetchDownloadUrl() {
                            fetch(`/jobWorkflow/download/${this.id}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.downloadUrl = data.downloadUrl;
                                    } else {
                                        console.error('Failed to fetch download URL');
                                    }
                                })
                                .catch(error => console.error('Error fetching download URL:', error));
                            }
                        }"
                        x-init="fetchDownloadUrl()"
                    >
                    <template x-if="downloadUrl">
                        <a :href="downloadUrl" class="underline" download> {{ $workflow->approval_doc }} </a>
                    </template>
                    <span x-show="!downloadUrl" class="text-gray-500">N/A</span>
                </div></td>
                <td class="py-4 px-4 text-center text-sm">
                  <div x-data="{ emailSent: {{ $workflow->email_sent }}, status: '{{ $workflow->status }}' }">
                     <template x-if="(status == 'Pending' && emailSent == 1)">
                        <button
                            @click="$dispatch('open-modal', { rowId: {{ $workflow->id }} })"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Accept
                        </button>
                      </template>

                    <template x-if="(status == 'Pending' && emailSent == 1)">
                        <button
                            @click="$dispatch('open-rejectmodal', { rowId: {{ $workflow->id }} })"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Reject
                        </button>
                    </template>

                    <template x-if="!(status == 'Pending')">
                        <span class="text-gray-500">{{ $workflow->status }}</span>
                    </template>
                  </div>
                </td>
            </tr>

              @endforeach
            </tbody>
          </table>
      </div>
       </div>
       <div x-show="tab === 'vendorrelease'"   class="w-full gap-4">
        <div x-data="{
        selectedVendor: '',
        vendor_id : '',
        showErrors: false,
        jobID: '{{ $job->id }}',
        errors: {},
        isFieldValid(field) {
            return !this.errors[field];
        },
        getErrorMessageById(field) {
            return this.errors[field] || '';
        },
        validate() {
            this.errors = {};
            var vendor_id = $('#vendor').val();
            if (!vendor_id) {
                this.errors.vendor = 'Please select a vendor.';
                this.showErrors = true;
            } else {
                this.showErrors = true;
            }
        },
        submitForm(event) {
            event.preventDefault();
            this.validate();
            if (Object.keys(this.errors).length === 0) {
                const formData = new FormData();
                formData.append('vendor_id', $('#vendor').val());
                formData.append('job_id', this.jobID);

                const url = '/admin/releaseJobVendor';
                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
            }
        }
    }">

    <form @submit="submitForm" id="generalformwizard">
        <label class="block mb-2">Vendors <span class="text-red-500">*</span></label>
        <select
            x-ref="selectedVendor"
            name="vendor_id"
            x-model="selectedVendor"
            class="w-50 select2-single custom-style"
            id="vendor"
        >
            <option value="">Select Vendor</option>
            @foreach ($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->first_name.' '.$vendor->last_name }}</option>
            @endforeach
        </select>
        <p x-show="showErrors && !isFieldValid('vendor')" class="text-red-500 text-sm mt-1"
           x-text="getErrorMessageById('vendor')"></p>

        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Submit
        </button>
    </form>

    <!-- Content -->
    <div class="p-4">
        <!-- Table -->
        <div class="overflow-x-auto" x-data="{ rows: {{ json_encode($vendorRelease) }} }">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Sr. #</th>
                        <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Vendor Name</th>
                        <th class="py-4 px-4 text-center font-semibold text-sm text-gray-600">Release Date/Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(row, index) in rows" :key="index">
                        <tr>
                            <td class="py-4 px-4 text-center text-sm"><span x-text="index + 1"></span></td>
                            <td class="py-4 px-4 text-center text-sm">
                                <span x-text="row.vendor_name.first_name + ' ' + row.vendor_name.last_name"></span>
                            </td>
                            <td class="py-4 px-4 text-center text-sm"><span x-text="row.formatted_job_released_time"></span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>




      </div>

         <div
              x-data="{
              openModal: false,
              currentRowId: '',
              job_id:{{$job->id}},
              reason: '',
              note: '',
              errors: {},
              handleFileUpload(event) {
                this.file = event.target.files[0];
              },
              validateForm() {
                this.errors = {};
                if (!this.note.trim()) this.errors.note = 'Please enter a note';
                return Object.keys(this.errors).length === 0;
              },
              submitForm() {
                if (this.validateForm()) {
                  let formData = new FormData();

                  formData.append('note', this.note);
                  formData.append('workflow_id', this.currentRowId);
                  formData.append('job_id', this.job_id);
                  if (this.file) {
                      formData.append('jobAttachment', this.file);
                  }
                  const url = '/admin/jobWorkFlowApprove';
                  ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);
                  this.openModal = false;
                }
              },
              clearError(field) {
                delete this.errors[field];
              }
            }"
              @open-modal.window="openModal = true; currentRowId = $event.detail.rowId"
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
                <!-- Header -->
                <div class="flex items-center justify-between border-b p-4">
                  <h2
                    class="text-xl font-semibold"
                    :id="$id('modal-title')"
                  >
                   Accept Workflow
                  </h2>
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
                    @csrf()
                    <input type="hidden" name="workflow_id" id="workflow_id" x-model="workflow_id" :value="currentRowId">
                    <input type="hidden" name="job_id" id="job_id" x-model="job_id" :value="job_id">
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
                      <div class="mb-4">
                          <label for="jobAttachment" class="block text-sm font-medium text-gray-700 mb-2">
                              Job Attachment
                          </label>
                          <input
                              type="file"
                              id="jobAttachment"
                              name="jobAttachment"
                              class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                              @change="handleFileUpload"
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

            <div
              x-data="{
              rejectModal: false,
              currentRowId: '',
              job_id : {{ $job->id }},
              reason: '',
              note: '',
              errors: {},
              validateForm() {
                this.errors = {};
                if (!this.reason.trim()) this.errors.reason = 'Please select reason';
                return Object.keys(this.errors).length === 0;
              },
              submitForm() {
                if (this.validateForm()) {
                  let formData = new FormData();

                  formData.append('note', this.note);
                  formData.append('workflow_id', this.currentRowId);
                  formData.append('job_id', this.job_id);
                  formData.append('reason', this.reason);
                  const url = '/admin/jobWorkFlowReject';
                  ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);
                  this.openModal = false;
                }
              },
              clearError(field) {
                delete this.errors[field];
              }
            }"
              @open-rejectmodal.window="rejectModal = true; currentRowId = $event.detail.rowId"
              x-show="rejectModal"
              @click.away="rejectModal = false"
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
                <!-- Header -->
                <div class="flex items-center justify-between border-b p-4">
                  <h2
                    class="text-xl font-semibold"
                    :id="$id('modal-title')"
                  >
                   Reject Workflow
                  </h2>
                  <button
                    @click="rejectModal = false"
                    class="text-gray-400 hover:text-gray-600 bg-transparent hover:bg-transparent"
                  >
                    &times;
                  </button>
                </div>

                <!-- Content -->
                <div class="p-4">
                  <form @submit.prevent="submitForm" id="generalformwizard">
                    @csrf()
                    <input type="hidden" name="workflow_id" id="workflow_id" x-model="workflow_id" :value="currentRowId">
                    <div class="mb-4">
                          <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">


                            </p>
                          </div>
                          <label
                            for="reason"
                            class="block text-sm font-medium text-gray-700 mb-1"
                          >
                            Reason for Rejection
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

                <!-- Footer -->
                <div class="flex justify-end space-x-2 border-t p-4">
                  <button
                    type="button"
                    @click="rejectModal = false"
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

    <script>

    function submitForm(event) {
        // Get form data
        // event.preventDefault();

        let formData = new FormData(event.target);

        const url = "/vendor/submission/store";
        ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);

    }


 function submitworkflowform(event) {
        alert('Form Submitted!'); // Just to ensure it's being called

        const formData = new FormData(event.target);
        formData.append('note', this.note);
        if (this.file) {
            formData.append('jobAttachment', this.file);
        }

        // Send form data using Fetch API or Axios
        fetch('{{ route("admin.workflow.jobWorkFlowUpdate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
      }

    document.addEventListener('alpine:init', () => {
        Alpine.data('workflowHandler', () => ({
            note: '', // To store the note input
            file: null, // To store the file

            submitworkflowform(event) {
                alert('Form Submitted!'); // Just to ensure it's being called

                const formData = new FormData();
                formData.append('note', this.note);
                if (this.file) {
                    formData.append('jobAttachment', this.file);
                }

                // Send form data using Fetch API or Axios
                fetch('{{ route("admin.workflow.jobWorkFlowUpdate") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            },

            handleFileUpload(event) {
                this.file = event.target.files[0];
            }
        }));

        Alpine.data('selectHandler', () => ({
        init() {
            this.initSelect2();
        },
        initSelect2() {
            this.$nextTick(() => {
                $(".select2-single").each((index, element) => {
                    const fieldName = $(element).data("field");
                    $(element).select2({
                        width: "100%",
                    });
                });
            });
        }
    }));
    });

    // Function to initialize Select2 outside of Alpine context
function initSelect2() {
    $(".select2-single").each((index, element) => {
        const fieldName = $(element).data("field");
        $(element).select2({
            width: "100%",
        });
    });
}

// Initialize Select2 on page load
document.addEventListener('DOMContentLoaded', function() {
    initSelect2();
    function toggleSidebar()
            {
                // Assuming you want to toggle selectedUser state
                this.selectedUser = this.selectedUser ? 'user' : 'user';
            }
    $(document).on('click', '.job-detail-trigger', function (e) {
                e.preventDefault();
                let jobId = $(this).data('id');
                openJobDetailsModal(jobId);
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
                                console.log(event.detail.data);

                                document.dispatchEvent(event);
                        })
                        .catch(error => console.error('Error:', error));

            }

            $(document).on('click', '.submission-detail-trigger', function (e) {
                e.preventDefault();
                let submissionId = $(this).data('id');
                openSubmissionDetailsModal(submissionId);
            });

            function openSubmissionDetailsModal(submissionId) {
                console.log(submissionId);
                fetch(`/submission-details/${submissionId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        const event = new CustomEvent('submission-details-updated', {
                            detail: data,
                            bubbles: true,
                            composed: true
                        });
                        document.dispatchEvent(event);
                    })
                    .catch(error => console.error('Error fetching submission details:', error));
            }
});



</script>


  @endsection
