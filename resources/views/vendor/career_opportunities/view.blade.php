@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div x-data="{ tab: 'activejobs' }" class="bg-white mx-4 my-8 rounded p-8">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold"></h2>
                    @if(jobVendSubmissionLimit($job->id) < $job->num_openings )

                    <div class="flex space-x-2">
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{route('vendor.submission.create', ['id' => $job->id]) }}'"
                        >
                            {{translate('Create Submission')}}
                        </button>
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{route('vendor.career-opportunities.index') }}'"
                        >
                            {{translate('Back to list of Career Opportunities')}}
                        </button>
                    </div>
                    @endif
                </div>
                <ul
                    class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
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
                        <span class="capitalize">{{translate('Submission')}} </span>
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
                </ul>
            </div>
            <div x-show="tab === 'activejobs'" class="flex w-full gap-4">
                <div class="flex w-full gap-4">
                    <!-- Left Column -->
                    <div
                        class="w-1/3 p-[30px] rounded border"
                        :style="{'border-color': 'var(--primary-color)'}"
                    >
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
                        @if($job->internal_notes)
                            <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                                <p class="color-[#202124] font-light">{{translate('Internal Notes')}}</p>
                                <div class="mt-4">
                                    <ul class="color-[#202124] font-light">
                                        {!! $job->internal_notes !!}
                                    </ul>
                                </div>
                            </div>
                        @endif
                        @if($job->skills)
                            <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                                <p class="color-[#202124] font-light">{{translate('Skills')}}</p>
                                <div class="mt-4">
                                    <ul class="color-[#202124] font-light">
                                        {!! $job->skills !!}
                                    </ul>
                                </div>
                            </div>
                        @endif
                        @if($job->description)
                            <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                                <p class="color-[#202124] font-light">{{translate('Job Description')}}</p>
                                <div class="mt-4">
                                    <ul class="color-[#202124] font-light">
                                        {!! $job->description !!}
                                    </ul>
                                </div>
                            </div>
                        @endif
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
                                    <h4 class="font-medium">{{translate('Job Status:')}}</h4>
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
                                    <p class="font-light">{{ $job->hiringManager->full_name }} </p>
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
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('Expenses Allowed?')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{ $job->expenses_allowed ?? 'N/A' }}</p>
                                </div>
                            </div>
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
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="w-2/4">
                                <h4 class="font-medium">{{translate('Expected Total Cost of Engagement:')}}</h4>
                            </div>
                            <div class="w-2/4">
                                <p class="font-light">{{ $job->expected_cost }}</p>
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
                                <p class="font-light">{{ formatDateTime($job->created_at ?? 'N/A') }}</p>
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
                        @if(!empty($job->job_details) && $job->job_details != '[]')
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
                            @php
                                $fieldLabels = collect($formFields)->pluck('label', 'name')->toArray();
                            @endphp
                            @foreach ($jobDetails as $key => $value)
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{ $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}:</h4>
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

            <div x-show="tab === 'submission'">
                @include('vendor.career_opportunities.submission')
            </div>

            <div x-show="tab === 'interview'">
                @include('vendor.career_opportunities.interview')
            </div>

            <div x-show="tab === 'offer'">
                @include('vendor.career_opportunities.offer')
            </div>

            <div x-show="tab === 'workorder'">
                @include('vendor.career_opportunities.workorder')
            </div>
        </div>
    </div>
@endsection
