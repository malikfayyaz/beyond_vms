@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div class="">
            <div class="bg-white mx-4 my-8 rounded p-8">
                @include('client.layouts.partials.alerts')
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
                            >{{translate('WorkOrder information')}}</span
                            >
                        </h3>
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('Contractor Name:')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->consultant->full_name}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('WorkOrder Status:')}}</h4>
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
                                    <h4 class="font-medium">{{translate('Job Profile:')}}</h4>
                                </div>
                                <div class="w-2/4" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
                                    <p class="font-light">
                                    <a class="text-blue-400 font-semibold cursor-pointer"
                                        onclick="openJobDetailsModal({{ $workorder->careerOpportunity->id }})"
                                        >{{$workorder->careerOpportunity->title}} ({{$workorder->careerOpportunity->id}})</a
                                    ></p>
                                    <x-job-details />
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('Location of Work:')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">
                                        {{$workorder->location->name}}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('Start Date:')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{formatDate($workorder->start_date)}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('End Date:')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{formatDate($workorder->end_date)}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">{{translate('Location Tax:')}}</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{translate('N/A')}}</p>
                                </div>
                            </div>
                            {{--<div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Original Start Date:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">11/01/2024</p>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div
                        class="w-4/6 p-[30px] rounded border"
                        :style="{'border-color': 'var(--primary-color)'}"
                    >
                        <h3 class="flex items-center gap-2 mb-4">
                            <i
                                class="fa-regular fa-clock"
                                :style="{'color': 'var(--primary-color)'}"
                            ></i
                            ><span
                                class="capitalize"
                                :style="{'color': 'var(--primary-color)'}"
                            >{{translate('Onboarding document background screening')}}</span
                            >
                        </h3>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="">
                                <h4 class="font-medium capitalize mb-4">
                                    {{translate('Gallagher - Contingent Worker acknowledgements')}}
                                    (<span>isrvr.com</span>)
                                </h4>
                                <a
                                    href="#"
                                    class="capitalize text-blue-400 hover:text-blue-500"
                                >{{translate('Click here for the contingent worker compliance
                                    acknowledgement!')}}</a
                                >
                            </div>
                        </div>
                        @php
                            $disabled = true;
                        @endphp
                        <div x-data="workOrderForm">
                            <form
                                action="#"
                                class="mt-4"
                                id="generalformwizard"
                                @submit.prevent="validateForm"
                            >
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2">{{translate('First Name')}}</label>
                                        <input
                                            x-model="formData.first_name"
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="First Name"
                                            disabled
                                            id="candidateFirstName"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2">{{translate('Middle Name')}}</label>
                                        <input
                                            x-model="formData.middle_name"
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="Middle Name"
                                            disabled
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2">{{translate('Last Name')}}</label>
                                        <input
                                            x-model="formData.last_name"
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="Last Name"
                                            id="candidateLastName"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2 capitalize"
                                        >{{translate('Personal email address')}}
                                            <span class="text-red-500">*</span></label
                                        >
                                        <input
                                            type="text"
                                            x-model="formData.personalEmailAddress"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="Enter personal email address N/A"
                                            id="personalEmailAddress"
                                            disabled
                                        />
                                        <p
                                            x-show="errors.personalEmailAddress"
                                            class="text-red-500 text-sm mt-1"
                                            x-text="errors.personalEmailAddress"
                                        ></p>
                                    </div>
                                </div>
                                @php $vendorrecords = $workorder->vendor->teamMembers;

                                @endphp
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2"
                                        >{{translate('Account Manager')}}
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.accountManager"
                                            class="w-full select2-single custom-style"
                                            data-field="accountManager"
                                            id="accountManager"
                                            disabled
                                        >

                                            <option value="">{{translate('Select Account Manager')}}</option>

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
                                        <p
                                            x-show="errors.accountManager"
                                            class="text-red-500 text-sm mt-1"
                                            x-text="errors.accountManager"
                                        ></p>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2"
                                        >{{translate('Recruitment Manager')}}
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.recruitmentManager"
                                            class="w-full select2-single custom-style"
                                            data-field="recruitmentManager"
                                            id="recruitmentManager"
                                            disabled
                                        >
                                            <option value="">{{translate('Select Recruitment Manager')}}</option>
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
                                        <p
                                            x-show="errors.recruitmentManager"
                                            class="text-red-500 text-sm mt-1"
                                            x-text="errors.recruitmentManager"
                                        ></p>
                                    </div>
                                </div>
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2 capitalize">{{translate('Location tax')}}</label>
                                        <input
                                            type="number"
                                            x-model="formData.locationTax"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="0.00%"
                                            id="locationTax"
                                            disabled
                                        />
                                    </div>
                                    <div class="flex-1"></div>
                                </div>
{{--                                <div class="flex items-center justify-between py-4 border-t">
                                    <div class="">
                                        <h4 class="font-medium capitalize mb-2">
                                            background screening
                                        </h4>
                                        <p>
                                            By clicking the boxes below i certify that the worker
                                            has met all compliance related items and is eligible to
                                            begin assignment at Gallagher
                                        </p>
                                    </div>
                                </div>--}}
{{--                                <div class="flex items-center justify-between py-4 border-t">
                                    <ul class="space-y-3">
                                        <li>
                                            <label
                                                class="flex items-center space-x-3 cursor-pointer"
                                            >
                                                <input
                                                    disabled
                                                    type="checkbox"
                                                    x-model="formData.codeOfConduct"
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer"
                                                />
                                                <span class="text-gray-700"
                                                >Code of Conduct
                            <span class="text-red-500">*</span></span
                                                >
                                                <p
                                                    x-show="errors.codeOfConduct"
                                                    class="text-red-500 text-sm mt-1"
                                                    x-text="errors.codeOfConduct"
                                                ></p>
                                            </label>
                                        </li>
                                        <li>
                                            <label
                                                class="flex items-center space-x-3 cursor-pointer"
                                            >
                                                <input
                                                    :disabled="isDisabled"
                                                    type="checkbox"
                                                    x-model="formData.dataPrivacy"
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer"
                                                />
                                                <span class="text-gray-700"
                                                >Data Privacy / Data Handling<span
                                                        class="text-red-500"
                                                    >*</span
                                                    ></span
                                                >
                                                <p
                                                    x-show="errors.dataPrivacy"
                                                    class="text-red-500 text-sm mt-1"
                                                    x-text="errors.dataPrivacy"
                                                ></p>
                                            </label>
                                        </li>
                                        <li>
                                            <label
                                                class="flex items-center space-x-3 cursor-pointer"
                                            >
                                                <input
                                                    :disabled="isDisabled"
                                                    type="checkbox"
                                                    x-model="formData.nonDisclosure"
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer"
                                                />
                                                <span class="text-gray-700"
                                                >Non-Disclosure<span class="text-red-500"
                                                    >*</span
                                                    ></span
                                                >
                                                <p
                                                    x-show="errors.nonDisclosure"
                                                    class="text-red-500 text-sm mt-1"
                                                    x-text="errors.nonDisclosure"
                                                ></p>
                                            </label>
                                        </li>
                                        <li>
                                            <label
                                                class="flex items-center space-x-3 cursor-pointer"
                                            >
                                                <input
                                                    :disabled="isDisabled"
                                                    type="checkbox"
                                                    x-model="formData.criminalBackground"
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer"
                                                />
                                                <span class="text-gray-700"
                                                >Criminal Background<span class="text-red-500"
                                                    >*</span
                                                    ></span
                                                >
                                                <p
                                                    x-show="errors.criminalBackground"
                                                    class="text-red-500 text-sm mt-1"
                                                    x-text="errors.criminalBackground"
                                                ></p>
                                            </label>
                                        </li>
                                    </ul>
                                </div>--}}
{{--                                <div class="flex items-center justify-between py-4 border-t">
                                    <div class="mt-4">
                                        <label
                                            for="document"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >Document</label
                                        >
                                        <input
                                            :disabled="isDisabled"
                                            type="file"
                                            @change="handleFileUpload"
                                            id="document"
                                            name="document"
                                            class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                        />
                                    </div>
                                </div>--}}
                                @isset($workorder->workorderbackground)
                                    <div class="flex items-center justify-between py-4 border-t">
                                        <table class="w-full border-collapse border">
                                            <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border p-2">{{translate('Document Check List')}}</th>
                                                <th class="border p-2">{{translate('Date')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="border p-2">{{translate('Code of Conduct')}}</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">{{translate('Data Privacy / Data Handling')}}</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">{{translate('Non-Disclosure')}}</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">{{translate('Criminal Background')}}</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="flex items-center justify-between py-4 border-t">
                                        <table class="w-full border-collapse border">
                                            <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border p-2">{{translate('Document Type')}}</th>
                                                <th class="border p-2">{{translate('Document List')}}</th>
                                                <th class="border p-2">{{translate('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if ($workorder->workorderbackground->file)
                                                <tr>
                                                    <td class="border p-2">{{translate('Document')}}</td>
                                                    <td class="border p-2"><a href="#">{{$workorder->workorderbackground->file }}</a></td>
                                                    <td class="border p-2">
                                                        <a href="{{ asset('storage/background_verify/' . $workorder->workorderbackground->file) }}" class="text-blue-500 hover:text-blue-700" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>

                                                        <button
                                                            type="button"
                                                            class="text-red-500 hover:text-red-700 ml-3 bg-transparent"
                                                            @click="deleteBackgroundFile({{ $workorder->workorderbackground->id }})"
                                                        >
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endisset

{{--                                <div class="flex-1 flex items-end gap-2">
                                    <button
                                        :disabled="isDisabled"
                                        @click="submitForm('save')"
                                        type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                    >
                                        Save
                                    </button>
                                    <button
                                        :disabled="isDisabled"
                                        @click="submitForm('saveAndSubmit')"
                                        type="button"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                    >
                                        Save & Submit
                                    </button>
                                </div>--}}
                            </form>
                        </div>
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
    </div>
@endsection
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("workOrderForm", () => ({
            formData: {
                personalEmailAddress: '{{ old('personalEmailAddress', $workorder->consultant->user->email ?? '') }}',
                first_name:'{{ old('first_name', $workorder->consultant->first_name ?? '') }}' ,
                middle_name:'{{ old('middle_name', $workorder->consultant->middle_name ?? '') }}' ,
                last_name:'{{ old('last_name', $workorder->consultant->last_name ?? '') }}' ,
                accountManager: "",
                recruitmentManager: "",
                codeOfConduct: false,
                dataPrivacy: false,
                nonDisclosure: false,
                criminalBackground: false,
            },
            errors: {},

            init() {
                this.initSelect2();
            },

            initSelect2() {
                this.$nextTick(() => {
                    $("#accountManager")
                        .select2({
                            width: "100%",
                        })
                        .on("select2:select", (e) => {
                            this.formData.accountManager = e.params.data.id;
                            this.errors.accountManager = "";
                        })
                        .on("select2:unselect", () => {
                            this.formData.accountManager = "";
                        });

                    $("#recruitmentManager")
                        .select2({
                            width: "100%",
                        })
                        .on("select2:select", (e) => {
                            this.formData.recruitmentManager = e.params.data.id;
                            this.errors.recruitmentManager = "";
                        })
                        .on("select2:unselect", () => {
                            this.formData.recruitmentManager = "";
                        });
                });
            },

            validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },

            validateForm() {
                this.errors = {};
                let isValid = true;

                console.log("Current form data:", this.formData);

                if (!this.formData.personalEmailAddress) {
                    this.errors.personalEmailAddress =
                        "Personal Email Address is required.";
                    isValid = false;
                    console.log("Personal Email validation failed");
                } else if (
                    !this.validateEmail(this.formData.personalEmailAddress)
                ) {
                    this.errors.personalEmailAddress =
                        "Please enter a valid email address.";
                    isValid = false;
                    console.log("Personal Email format validation failed");
                }

                if (!this.formData.accountManager) {
                    this.errors.accountManager = "Please select an Account Manager.";
                    isValid = false;
                    console.log("Account Manager validation failed");
                }

                if (!this.formData.recruitmentManager) {
                    this.errors.recruitmentManager =
                        "Please select a Recruitment Manager.";
                    isValid = false;
                    console.log("Recruitment Manager validation failed");
                }

                if (!this.formData.codeOfConduct) {
                    this.errors.codeOfConduct = "Please confirm Code of Conduct.";
                    isValid = false;
                    console.log("Code of Conduct validation failed");
                }

                if (!this.formData.dataPrivacy) {
                    this.errors.dataPrivacy =
                        "Please confirm Data Privacy / Data Handling.";
                    isValid = false;
                    console.log("Data Privacy validation failed");
                }

                if (!this.formData.nonDisclosure) {
                    this.errors.nonDisclosure = "Please confirm Non-Disclosure.";
                    isValid = false;
                    console.log("Non-Disclosure validation failed");
                }

                if (!this.formData.criminalBackground) {
                    this.errors.criminalBackground =
                        "Please confirm Criminal Background.";
                    isValid = false;
                    console.log("Criminal Background validation failed");
                }

                console.log("Validation result:", isValid ? "Passed" : "Failed");
                console.log("Errors:", this.errors);

                return isValid;
            },

            submitForm(action) {
                console.log("Submitting form...");
                const isValid = this.validateForm();
                if (isValid) {
                    if (action === "save") {
                        console.log("Form is valid. Saving...");
                        // Add your save logic here
                    } else if (action === "saveAndSubmit") {
                        console.log("Form is valid. Saving and submitting...");
                        // Add your save and submit logic here
                    }
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
