@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="ml-16">
            <div class="bg-white mx-4 my-8 rounded p-8">
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
                            >WorkOrder information</span
                            >
                        </h3>
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Contractor Name:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->consultant->full_name}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">WorkOrder Status:</h4>
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
                                    <h4 class="font-medium">Job ID:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->careeropportunity->id}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Job Profile:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->careeropportunity->title}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Location of Work:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">
                                        {{$workorder->location->name}}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Start Date:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->start_date}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">End Date:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">{{$workorder->end_date}}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-t">
                                <div class="w-2/4">
                                    <h4 class="font-medium">Location Tax:</h4>
                                </div>
                                <div class="w-2/4">
                                    <p class="font-light">N/A</p>
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
                            >Onboarding document background screening</span
                            >
                        </h3>
                        <div class="flex items-center justify-between py-4 border-t">
                            <div class="">
                                <h4 class="font-medium capitalize mb-4">
                                    Gallagher - Contingent Worker acknowledgements
                                    (<span>isrvr.com</span>)
                                </h4>
                                <a
                                    href="#"
                                    class="capitalize text-blue-400 hover:text-blue-500"
                                >click here for the contingent worker compliance
                                    acknowledgement!</a
                                >
                            </div>
                        </div>
                        <div x-data="workOrderForm">
                            <form
                                action="#"
                                class="mt-4"
                                id="generalformwizard"
                                @submit.prevent="validateForm"
                            >
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2">First Name</label>
                                        <input
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="{{$workorder->consultant->first_name}}"
                                            disabled
                                            id="candidateFirstName"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2">Middle Name</label>
                                        <input
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="{{$workorder->consultant->middle_name}}"
                                            disabled
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2">Last Name</label>
                                        <input
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="{{$workorder->consultant->last_name}}"
                                            id="candidateLastName"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2 capitalize"
                                        >personal email address
                                            <span class="text-red-500">*</span></label
                                        >
                                        <input
                                            type="text"
                                            x-model="formData.personalEmailAddress"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="Enter personal email address N/A"
                                            id="personalEmailAddress"
                                        />
                                        <p
                                            x-show="errors.personalEmailAddress"
                                            class="text-red-500 text-sm mt-1"
                                            x-text="errors.personalEmailAddress"
                                        ></p>
                                    </div>
                                </div>
                                <div class="flex space-x-4 mb-4">
                                    <div class="flex-1">
                                        <label class="block mb-2"
                                        >Account Manager
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.accountManager"
                                            class="w-full select2-single custom-style"
                                            data-field="accountManager"
                                            id="accountManager"
                                        >
                                            <option value="">Select a category</option>
                                            <option value="javascript">JavaScript</option>
                                            <option value="python">Python</option>
                                            <option value="java">Java</option>
                                            <option value="csharp">C#</option>
                                            <option value="ruby">Ruby</option>
                                        </select>
                                        <p
                                            x-show="errors.accountManager"
                                            class="text-red-500 text-sm mt-1"
                                            x-text="errors.accountManager"
                                        ></p>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2"
                                        >Recruitment Manager
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.recruitmentManager"
                                            class="w-full select2-single custom-style"
                                            data-field="recruitmentManager"
                                            id="recruitmentManager"
                                        >
                                            <option value="">Select a category</option>
                                            <option value="javascript">JavaScript</option>
                                            <option value="python">Python</option>
                                            <option value="java">Java</option>
                                            <option value="csharp">C#</option>
                                            <option value="ruby">Ruby</option>
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
                                        <label class="block mb-2 capitalize">location tax</label>
                                        <input
                                            type="number"
                                            x-model="formData.candidateFirstName"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="0.00%"
                                            id="candidateFirstName"
                                        />
                                    </div>
                                    <div class="flex-1"></div>
                                </div>
                                <div class="flex items-center justify-between py-4 border-t">
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
                                </div>
                                <div class="flex items-center justify-between py-4 border-t">
                                    <ul class="space-y-3">
                                        <li>
                                            <label
                                                class="flex items-center space-x-3 cursor-pointer"
                                            >
                                                <input
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
                                </div>
                                <div class="flex items-center justify-between py-4 border-t">
                                    <div class="mt-4">
                                        <label
                                            for="document"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >Document</label
                                        >
                                        <input
                                            type="file"
                                            id="document"
                                            name="document"
                                            class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                        />
                                    </div>
                                </div>
                                <div class="flex-1 flex items-end gap-2">
                                    <button
                                        @click="submitForm('save')"
                                        type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                    >
                                        Save
                                    </button>
                                    <button
                                        @click="submitForm('saveAndSubmit')"
                                        type="button"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                    >
                                        Save & Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection