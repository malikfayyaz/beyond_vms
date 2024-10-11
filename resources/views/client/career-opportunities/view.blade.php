@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div  x-data="{ tab: 'activejobs' }"  class="bg-white mx-4 my-8 rounded p-8">
            @if($job->jobStatus == 2)
              <div x-data="{
                    rejectionReason: '{{ $job->rejectionReason->title }}',
                    notes: '{{ $job->note_for_rejection }}',
                    rejectedBy: '{{ $job->rejectionUser->name }}',
                    rejectionDate: '{{ $job->date_rejected }}'
                }">
                    <div class="alert alert-danger">
                        <span class="bold">Rejection Reason:</span> <span x-text="rejectionReason"></span><br>
                        <span class="bold">Notes:</span> <span x-text="notes"></span><br>
                        <span class="bold">Rejected By:</span> <span x-text="rejectedBy"></span><br>
                        <span class="bold">Rejection Date:</span> <span x-text="rejectionDate"></span>
                    </div>
                </div>
              @endif
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold"></h2>
                <div class="flex space-x-2">
                    <form action="{{ route('client.career-opportunities.copy', $job->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize">
                            Copy Career Opportunity <i class="fas fa-copy"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="mb-4">
                <ul
                    class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
                >
                    <li class="flex justify-center">
                        <a
                            @click="tab = 'activejobs'" 
                            href="#page1"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4 tab === 'activejobs'"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                            <span class="capitalize">active jobs</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                            >
                                <span class="text-[10px]">156</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center items-center">
                        <a
                            href="#page2"
                            class="w-full flex justify-center items-center gap-3 bg-white rounded-lg shadow py-4"
                            :style="{'color': 'var(--primary-color)'}"
                        ><i class="fa-regular fa-registered"></i
                            ><span class="capitalize">Pending Release Job</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': 'var(--primary-color)'}"
                            >
                                <span class="text-[10px]">56</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                        @click="tab = 'jobworkflow'" 
                        :class="{ 'border-blue-500 text-blue-500': tab === 'jobworkflow' }" 
                        class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                        >
                          <i class="fa-solid fa-fill"></i>
                          <span class="capitalize">Workflow</span>
                          <div
                            class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                          >
                            <span class="text-[10px]">20</span>
                          </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#page1"
                            class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">filled jobs</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                            >
                                <span class="text-[10px]">20</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#page1"
                            class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                        >
                            <i class="fa-solid fa-lock"></i>
                            <span class="capitalize">closed jobs</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                            >
                                <span class="text-[10px]">2957</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#page1"
                            class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                        >
                            <i class="fa-solid fa-spinner"></i>
                            <span class="capitalize">pending - PMO</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                            >
                                <span class="text-[10px]">0</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#page1"
                            class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                        >
                            <i class="fas fa-drafting-compass"></i>
                            <span class="capitalize">draft</span>
                            <div
                                class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                            >
                                <span class="text-[10px]">30</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
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
                    </li>
                </ul>
            </div>
            <div class="flex w-full gap-4" x-show="tab === 'activejobs'">
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
                        >Regular Hours Cost</span
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
                        >Single Resource Cost</span
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
                        >All Resources Cost</span
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
                                <span class="text-white">Business Unit</span>
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
                    {{--              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
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
                        <p class="color-[#202124] font-light">Internal Notes</p>
                        <div class="mt-4">
                            <ul class="color-[#202124] font-light">
                                {{ $job->internal_notes }}
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                        <p class="color-[#202124] font-light">Skills</p>
                        <div class="mt-4">
                            <ul class="color-[#202124] font-light">
                                {{ $job->skills }}
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                        <p class="color-[#202124] font-light">
                            Pre-Identified Candidate
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
                    >Pre-Identified Candidate?</span
                    >
                            </div>
                            <div class="w-2/4 pl-4">
                                <span class="color-[#202124] font-light">{{$job->pre_candidate}}</span>
                            </div>
                        </div>
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
                        >Job Info</span
                        >
                    </h3>
                    <div class="flex flex-col">
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Job Title:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->title }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Hiring Manager:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{  $job->hiringManager->full_name  }} </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Job Title for Email Signature:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{$job->alternative_job_title}}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Work Location:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">
                                    {{ locationName($job->location_id)}}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Division:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->division->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Region/Zone:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->regionZone->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Branch:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->branch->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Job Code:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->job_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Category:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->category->title ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Travel Required:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->travel_required ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Business Reason:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->businessReason->title ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Time System:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->jobType->title ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Client Billable:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->client_billable ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Expenses Allowed?</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->expenses_allowed ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Remote Candidate:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->remote_option ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Number of Opening(s):</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->num_openings ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">Worker Type:</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->workerType->title ?? 'N/A' }}</p>
                            </div>
                        </div>
                        {{--                <div class="flex items-center justify-between py-4 border-t">
                                          <div class="w-2/4">
                                            <h4 class="font-medium">Job Family:</h4>
                                          </div>
                                          <div class="w-2/4">
                                            <p class="font-light">777007 - Contingent Worker-Claims</p>
                                          </div>
                                        </div>--}}
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">GL Account:</h4>
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
                        >Job Duration</span
                        >
                    </h3>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Work Days / Week:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->day_per_week ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Total Hours/Week:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->hours_per_week ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Estimated Hours / Day:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->hours_per_day ?? 'N/A' }}</p>
                        </div>
                    </div>
                    {{--              <div class="flex items-center justify-between py-4 border-t">
                                    <div class="w-2/4">
                                      <h4 class="font-medium">Total Time:</h4>
                                    </div>
                                    <div class="w-2/4">
                                      <p class="font-light">1040</p>
                                    </div>
                                  </div>--}}
                    <div class="flex items-center justify-between py-4 border-y">
                        <div class="w-2/4">
                            <h4 class="font-medium">Job Duration:</h4>
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
                        ><span :style="{'color': 'var(--primary-color)'}">Rates</span>
                    </h3>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Unit of Measure:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->paymentType->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Currency:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->currency->symbol->title ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Minimum Bill Rate:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->min_bill_rate }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Maximum Bill Rate:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->max_bill_rate }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-y">
                        <div class="w-2/4">
                            <h4 class="font-medium">Time Type:</h4>
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
                        >Job Publish Info</span
                        >
                    </h3>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Job Created at:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->created_at ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t">
                        <div class="w-2/4">
                            <h4 class="font-medium">Job Created By:</h4>
                        </div>
                        <div class="w-2/4">
                            <p class="font-light">{{ $job->createdBy->name ?? 'N/A' }}</p>
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
                  Sr. #
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approver Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approval Type
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approval Required
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approval/Rejected By
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Release Date & Time
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approved/Rejected Date & Time
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approval Notes
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Approval Document
                </th>

                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Action
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

                <td class="py-4 px-4 text-center text-sm">{{ isset($workflow->created_at) ? date('Y-m-d H:i:s',strtotime($workflow->created_at)) : 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ $workflow->approved_datetime ?? 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">{{ $workflow->approval_notes ?? 'N/A' }}</td>
                <td class="py-4 px-4 text-center text-sm">
                    <div x-data="{
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
                </div>

                </td>
                <td class="py-4 px-4 text-center text-sm">
                    <div x-data="{ status: '{{ $workflow->status }}', emailSent: {{ $workflow->email_sent }}, clientId: {{ $workflow->client_id }}, loginClientId: {{ $loginClientid }}  }">
                        <template x-if="(status == 'Pending' && emailSent == 1 && loginClientId == clientId)">
                            <button
                                @click="$dispatch('open-modal', { rowId: {{ $workflow->id }} })"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Accept
                            </button>
                        </template>

                        <template x-if="(status == 'Pending' && emailSent == 1 && loginClientId == clientId)">
                            <button
                                @click="$dispatch('open-rejectmodal', { rowId: {{ $workflow->id }} })"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Reject
                            </button>
                        </template>
                       <template x-if="!(status == 'Pending' && emailSent == 1 && loginClientId == clientId )">
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
    </div>

     
    </div>
         <div
              x-data="{
              openModal: false,
              currentRowId: '',
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
                  if (this.file) {
                      formData.append('jobAttachment', this.file);
                  }
                  const url = '/client/jobWorkFlowApprove';
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
                  formData.append('reason', this.reason);
                  const url = '/client/jobWorkFlowReject';
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
@endsection
