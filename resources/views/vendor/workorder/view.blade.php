@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="ml-16">

            <div class="bg-white mx-4 my-8 rounded p-8">
            @include('vendor.layouts.partials.alerts')
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
                      >{{\App\Models\CareerOpportunitiesWorkorder::getWorkorderStatus($workorder->status)}}</span
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
                        @php
                                $disabled = true;
                                if ( $workorder->verification_status == 0 ) {
                                    $disabled = false;
                                }
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
                                        <label class="block mb-2">First Name</label>
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
                                        <label class="block mb-2">Middle Name</label>
                                        <input
                                            x-model="formData.middle_name"
                                            type="text"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="Middle Name"
                                            disabled
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label class="block mb-2">Last Name</label>
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
                                        >personal email address
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
                                        >Account Manager
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.accountManager"
                                            class="w-full select2-single custom-style"
                                            data-field="accountManager"
                                            id="accountManager"
                                            :disabled="isDisabled"
                                        >

                                            <option value="">Select Account Manager</option>

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
                                        >Recruitment Manager
                                            <span class="text-red-500">*</span></label
                                        >
                                        <select
                                            x-model="formData.recruitmentManager"
                                            class="w-full select2-single custom-style"
                                            data-field="recruitmentManager"
                                            id="recruitmentManager"
                                             :disabled="isDisabled"
                                        >
                                            <option value="">Select Recruitment Manager</option>
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
                                        <label class="block mb-2 capitalize">location tax</label>
                                        <input
                                            type="number"
                                            x-model="formData.locationTax"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                            placeholder="0.00%"
                                            id="locationTax"
                                             :disabled="isDisabled"
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
                                                 :disabled="isDisabled"
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
                                </div>
                                <div class="flex items-center justify-between py-4 border-t">
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
                                </div>
                                @isset($workorder->workorderbackground)
                                <div class="flex items-center justify-between py-4 border-t">
                                    <table class="w-full border-collapse border">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border p-2">Document Check List</th>
                                                <th class="border p-2">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border p-2">Code of Conduct</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">Data Privacy / Data Handling</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">Non-Disclosure</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border p-2">Criminal Background</td>
                                                <td class="border p-2">{{formatDate($workorder->workorderbackground->created_at) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex items-center justify-between py-4 border-t">
                                    <table class="w-full border-collapse border">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border p-2">Document Type</th>
                                                <th class="border p-2">Document List</th>
                                                <th class="border p-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($workorder->workorderbackground->file)
                                            <tr>
                                                <td class="border p-2">Document</td>
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

                                <div class="flex-1 flex items-end gap-2">
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
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
<script>
      document.addEventListener("alpine:init", () => {
        Alpine.data("workOrderForm", () => ({
            isDisabled: @json($disabled),
          formData: {
            personalEmailAddress: '{{ old('personalEmailAddress', $workorder->consultant->user->email ?? '') }}',
            first_name:'{{ old('first_name', $workorder->consultant->first_name ?? '') }}' ,
            middle_name:'{{ old('middle_name', $workorder->consultant->middle_name ?? '') }}' ,
            last_name:'{{ old('last_name', $workorder->consultant->last_name ?? '') }}' ,
            accountManager: '{{ old('accountManager', $workorder->submission->emp_msp_account_mngr ?? '') }}',
            recruitmentManager: '{{ old('recruitmentManager', $workorder->submission->emp_msp_account_mngr ?? '') }}',
            workorder_id:'{{ old('workorder_id', $workorder->id ?? '') }}' ,
            locationTax:'{{ old('locationTax', $workorder->location_tax ?? '') }}',
            fileUpload:'',
            codeOfConduct: {{ old('codeOfConduct', optional($workorder->workorderbackground)->code_of_conduct ? 'true' : 'false') }},
            dataPrivacy: {{ old('dataPrivacy', optional($workorder->workorderbackground)->data_privacy ? 'true' : 'false') }},
            nonDisclosure: {{ old('nonDisclosure', optional($workorder->workorderbackground)->non_disclosure ? 'true' : 'false') }},
            criminalBackground: {{ old('criminalBackground', optional($workorder->workorderbackground)->criminal_background ? 'true' : 'false') }},
          },
          errors: {},

          init() {
            this.initSelect2();
          },

          handleFileUpload(event) {
            const file = event.target.files[0];
            this.formData.fileUpload = file || null;
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

            deleteBackgroundFile(id) {
                if (confirm("Are you sure you want to delete this file?")) {
                    const url = `{{ route('vendor.workorderbackground.destroy', ':id') }}`.replace(':id', id);
                    console.log(url);
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Include CSRF token
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect_url) {
                            // Redirect to the URL returned by the server
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Failed to delete the file');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong.');
                    });
                }
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
                let formData = new FormData();
                Object.keys(this.formData).forEach((key) => {
                  if (Array.isArray(this.formData[key])) {
                    // If the key is an array (like businessUnits), handle each item
                    this.formData[key].forEach((item, index) => {
                      formData.append(`${key}[${index}]`, JSON.stringify(item));
                    });
                  } else {
                    formData.append(key, this.formData[key]);
                  }
                });
                formData.append('type', 'save');
                const url = "/vendor/workorder/store";
              ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);

                console.log("Form is valid. Saving...");
                // Add your save logic here
              } else if (action === "saveAndSubmit") {
                let formData = new FormData();
                Object.keys(this.formData).forEach((key) => {
                  if (Array.isArray(this.formData[key])) {
                    // If the key is an array (like businessUnits), handle each item
                    this.formData[key].forEach((item, index) => {
                      formData.append(`${key}[${index}]`, JSON.stringify(item));
                    });
                  } else {
                    formData.append(key, this.formData[key]);
                  }
                });
                formData.append('type', 'saveAndSubmit');
                const url = "/vendor/workorder/store";
              ajaxCall(url,'POST', [[onSuccess, ['response']]], formData);
                console.log("Form is valid. Saving and submitting...");
                // Add your save and submit logic here
              }
            } else {
              console.log("Form validation failed");
            }
          },
        }));
      });
    </script>
