@extends('admin.layouts.app')
@vite([ 'resources/js/job/job.js'])
@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        @php         $user = Auth::user();
        $sessionrole = session('selected_role');
        @endphp
        <script>
            var sessionrole = "{{ $sessionrole }}";
        </script>

        <div class="bg-white mx-4 my-8 rounded p-8" x-data='quickcreate({{ $editIndex ?? "null" }})'  x-init="mounted()">
            <form @submit.prevent="submitForm" id="quickaddjob" method="POST">
                <div>
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Job Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">Job Profile
                                    <span class="text-red-500">*</span></label>
                                <select x-ref="jobProfile" name="jobProfile" x-model="formData.jobProfile"
                                    class="w-full select2-single custom-style" data-field="jobProfile"
                                    id="jobProfile">
                                    <option value="">Select a profile</option>
                                    @foreach ($pwt_jobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach

                                </select>
                                <p x-show="showErrors && !isFieldValid('jobProfile')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('jobProfile')"></p>
                            </div>
                            
                            <div class="flex-1">
                                <label class="block mb-2">Hiring Manager <span class="text-red-500">*</span></label>
                                @php $clients_hiring = \App\Models\Client::where('profile_status', 1)
                                ->orderBy('first_name', 'ASC')
                                ->get(); @endphp
                                <select x-ref="hiringManager" name="hiring_manager" x-model="formData.hiringManager"
                                    class="w-full select2-single custom-style" data-field="hiringManager" id="hiringManager">
                                    <option value="">Select Hiring Manager</option>
                                    @foreach ($clients_hiring as $key => $value)
                                    <option value="{{ $value->id }}">{{  $value->first_name.' '.$value->last_name; }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('hiringManager')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('hiringManager')"></p>
                            </div>
                       
                            <div class="flex-1">
                                <label class="block mb-2">Work Location <span class="text-red-500">*</span></label>
                                @php $location = \App\Models\Location::byStatus();
                                $currencies = getActiveRecordsByType('currency')->load('setting'); @endphp
                                <select x-ref="workLocation" name="location_id" x-model="formData.workLocation"
                                    class="w-full select2-single custom-style" data-field="workLocation" id="workLocation">
                                    <option value="">Select Work Location</option>

                                    @foreach ($location as $key => $value)
                                    <option value="{{ $value->id }}">{{ locationName($value->id) }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('workLocation')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('workLocation')"></p>
                            </div>
                        </div>
                    </div> 

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Vendor Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">Vendor <span class="text-red-500">*</span></label>
                                @php $vendors = \App\Models\Vendor::where('profile_status', 1)
                                ->orderBy('first_name', 'ASC')
                                ->get(); @endphp
                                <select x-ref="vendor" name="vendor" x-model="formData.vendor"
                                    class="w-full select2-single custom-style" data-field="vendor" id="vendor">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $key => $value)
                                    <option value="{{ $value->id }}">{{  $value->first_name.' '.$value->last_name; }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('vendor')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('vendor')"></p>
                            </div>
                       
                            <div class="flex-1">
                                <label class="block mb-2">Account Manager</label>
                                <select x-ref="accManager" name="accManager" x-model="formData.accManager"
                                    class="w-full select2-single custom-style" data-field="accManager" id="accManager">
                                    <option value="">Select Account Manager</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label for="subDate" class="block mb-2">Date of Submission
                                    <span class="text-red-500">*</span></label>
                                <input name="subDate" id="subDate"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    x-ref="subDate" type="text" x-model="formData.subDate"
                                    placeholder="Select start date" />
                                <p x-show="showErrors && !isFieldValid('subDate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('subDate')"></p>
                            </div>
                        </div>
                    </div> 
                    
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Offer Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label for="offStartDate" class="block mb-2">Start Date
                                    <span class="text-red-500">*</span></label>
                                <input name="offStartDate" id="offStartDate"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    x-ref="offStartDate" type="text" x-model="formData.offStartDate"
                                    placeholder="Select start date" />
                                <p x-show="showErrors && !isFieldValid('offStartDate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('offStartDate')"></p>
                            </div>
                            <div class="flex-1">
                                <label for="offEndDate" class="block mb-2">End Date <span
                                        class="text-red-500">*</span></label>
                                <input name="offEndDate" id="offEndDate"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    x-ref="offStartDate" type="text" placeholder="Select end date" x-model="formData.offEndDate" />
                                <p x-show="showErrors && !isFieldValid('offEndDate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('offEndDate')"></p>
                            </div>
                        </div>
                    </div> 

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Candidate Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">New/Existing Candidate?
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="new_exist" x-ref="newExist"
                                    x-model="formData.newExist" class="w-full select2-single custom-style"
                                    data-field="newExist" id="newExist">
                                    <option value="">Select</option>
                                    <option value="1">New</option>
                                    <option value="2">Existing</option>
                                </select>
                                <p x-show="showErrors && !isFieldValid('newExist')"
                                    class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('newExist')">
                                </p>
                            </div>

                            <div class="flex-1" x-show="formData.newExist === '2'">
                                <label class="block mb-2"> Existing Candidate
                                <span class="text-red-500">*</span></label>
                                @php 
                                    $candidates = \App\Models\Consultant::where('profile_status', 1)
                                    ->orderBy('first_name', 'ASC')
                                    ->get();
                                @endphp
                                <select x-ref="existingCandidate" name="existingCandidate" x-model="formData.existingCandidate"
                                    class="w-full select2-single custom-style" data-field="existingCandidate" id="existingCandidate">
                                    <option value="">Select Existing Candidate</option>
                                    @foreach ($candidates as $candidate)
                                        <option value="{{ $candidate->id }}">{{ $candidate->first_name.' '.$candidate->last_name }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('existingCandidate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('existingCandidate')"></p>
                            </div>

                            <div class="flex-1">
                                <label class="block mb-2">Physical Work Location 
                                    <span class="text-red-500">*</span>
                                </label>
                                @php $phylocation = \App\Models\Country::all();
                                @endphp
                                <select x-ref="phyLocation" name="phyLocation" x-model="formData.phyLocation"
                                    class="w-full select2-single custom-style" data-field="phyLocation" id="phyLocation">
                                    <option value="">Select Work Location</option>

                                    @foreach ($phylocation as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('phyLocation')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('phyLocation')"></p>
                            </div>
                            <div class="flex-1" x-show="formData.newExist !== '2'"></div>
                        </div>
                        <div class="mt-4" x-show="formData.newExist === '1'">
                            <div class="flex space-x-4 mb-4">
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate First Name
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="pre_name" x-model="formData.candidateFirstName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                        placeholder="Enter first name" id="candidateFirstName"/>
                                    <p x-show="showErrors && !isFieldValid('candidateFirstName')"
                                        class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('candidateFirstName')">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Middle Name</label>
                                    <input name="pre_middle_name" type="text" x-model="formData.candidateMiddleName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none "
                                        placeholder="Enter middle name"/>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Last Name
                                        <span class="text-red-500">*</span></label>
                                    <input name="pre_last_name" type="text" x-model="formData.candidateLastName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none "
                                        placeholder="Enter last name" id="candidateLastName"/>
                                    <p x-show="showErrors && !isFieldValid('candidateLastName')"
                                        class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('candidateLastName')"></p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mb-4">
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Phone
                                        <span class="text-red-500">*</span></label>
                                    <input name="candidate_phone" type="tel" x-model="formData.candidatePhone"
                                        x-on:input="formatPhoneNumber($event.target)"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none "
                                        placeholder="(XXXX) XXX-XXXX" id="candidatePhone"/>
                                    <p x-show="showErrors && !isFieldValid('candidatePhone')" class="text-red-500 text-sm mt-1"
                                        x-text="getErrorMessageById('candidatePhone')"></p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Email
                                        <span class="text-red-500">*</span></label>
                                    <input name="candidate_email" type="email" x-model="formData.candidateEmail"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none "
                                        placeholder="Enter email" id="candidateEmail"/>
                                    <p x-show="showErrors && !isFieldValid('candidateEmail')" class="text-red-500 text-sm mt-1"
                                        x-text="getErrorMessageById('candidateEmail')"></p>
                                </div>
                                <div class="flex-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        @if(isset($editIndex))
                            Update
                        @else
                            Submit
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function quickcreate(editIndex) {
            console.log(editIndex);
            return {
                editIndex,
                showErrors: false,

                formData: {
                    jobProfile: '',
                    hiringManager: '',
                    workLocation: '',
                    vendor: '',
                    accManager: '',
                    subDate: '',
                    offStartDate: '',
                    offEndDate: '',
                    newExist: '',
                    phyLocation: '',
                    candidateFirstName: '',
                    candidateMiddleName: '',
                    candidateLastName: '',
                    candidatePhone: '',
                    candidateEmail: '',
                    existingCandidate: '',
                },

                isFieldValid(fieldId) {
                    const fieldValue = this.formData[fieldId];
                    
                    if (['candidateMiddleName','accManager'].includes(fieldId)) {
                        return true; 
                    }   

                    if (this.formData.newExist === "1") { 
                        this.formData.existingCandidate = "";
                       
                        if (['candidateFirstName', 'candidateLastName', 'candidatePhone', 'candidateEmail'].includes(fieldId)) {
                            return fieldValue && fieldValue.trim() !== "";
                        }
                        
                        if (fieldId === 'existingCandidate') {
                            return true;
                        }
                    } else if (this.formData.newExist === "2") {
                        this.formData.candidateFirstName = "";
                        this.formData.candidateMiddleName = "";
                        this.formData.candidateLastName = "";
                        this.formData.candidatePhone = "";
                        this.formData.candidateEmail = "";
                       
                        if (['existingCandidate'].includes(fieldId)) {
                            return fieldValue && fieldValue.trim() !== "";
                        }

                        if (['candidateFirstName', 'candidateLastName', 'candidatePhone', 'candidateEmail'].includes(fieldId)) {
                            return true;
                        }
                    }
                    
                    if (fieldValue === null || fieldValue === undefined) {
                        // For null or undefined fields, return false
                        return false; 
                    } else if (typeof fieldValue === 'string') {
                        // For string fields, check if the trimmed value is not empty
                        return fieldValue.trim() !== ""; 
                    } else if (typeof fieldValue === 'number') {
                        // For number fields, check if the value is not null or undefined
                        return fieldValue !== null && fieldValue !== undefined;
                    } else {
                        // For other types, check if the value is not null or undefined
                        return fieldValue !== null && fieldValue !== undefined;
                    }
                },

                getErrorMessageById(fieldId) {
                    const errorMessages = {
                        jobProfile: 'Please select a job profile',
                        hiringManager: 'Please select a hiring manager',
                        workLocation: 'Please select a work location',
                        vendor: 'Please select a vendor',
                        subDate: 'Please select a submission date',
                        offStartDate: 'Please select a start date',
                        offEndDate: 'Please select an end date',
                        newExist: 'Please select new/existing candidate',
                        phyLocation: 'Please select a physical work location',
                        candidateFirstName: 'Please enter candidate first name',
                        candidateLastName: 'Please enter candidate last name',
                        candidatePhone: 'Please enter candidate phone number',
                        candidateEmail: 'Please enter candidate email',
                        existingCandidate: 'Please select an existing candidate',
                    };
                    return errorMessages[fieldId] || 'This field is required.';
                },

                init() {
                    this.initSelect2();
                    this.initFlatpickr();
                },
                initSelect2() {
                    $(".select2-single").each((index, element) => {
                        const fieldName = $(element).data("field");
                        if (this.formData[fieldName]) {
                            $(element).val(this.formData[fieldName]);
                        }
                        $(element)
                            .select2({
                            width: "100%",
                            })
                            .on("select2:select", (e) => {
                                this.formData[fieldName] = e.params.data.id;
                            })
                            .on("select2:unselect", () => {
                                this.formData[fieldName] = "";
                            });
                    });
                },

                initFlatpickr() {
                    flatpickr("#subDate", {
                        dateFormat: "m/d/Y",
                        defaultDate: this.formData.subDate,
                        onChange: (selectedDates, dateStr, instance) => {
                            this.formData.subDate = dateStr;
                        },
                    });

                    flatpickr("#offStartDate", {
                        dateFormat: "m/d/Y",
                        defaultDate: this.formData.offStartDate,
                        onChange: (selectedDates, dateStr, instance) => {
                            this.formData.offStartDate = dateStr;
                            this.offEndDate.set("minDate", dateStr);
                        },
                    });

                    this.offEndDate = flatpickr("#offEndDate", {
                        dateFormat: "m/d/Y",
                        defaultDate: this.formData.endDate || null,
                        onChange: (selectedDates, dateStr) => {
                        this.formData.endDate = dateStr;
                        },
                    });
                },

                mounted() {
                    console.log(window.$); // Verify jQuery is available
                    if (window.$) {
                        this.init();
                    } else {
                        console.error("jQuery is not available. Please make sure jQuery is loaded before this script.");
                    }
                },

                formatPhoneNumber(input) {
                    let phoneNumber = input.value.replace(/\D/g, "");
                    if (phoneNumber.length > 10) {
                        phoneNumber = phoneNumber.slice(0, 10);
                    }
                    if (phoneNumber.length >= 6) {
                        phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(
                        4,
                        7
                        )}-${phoneNumber.slice(7)}`;
                    } else if (phoneNumber.length >= 4) {
                        phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(4)}`;
                    }
                    input.value = phoneNumber;
                },
                
                submitForm() {
                    this.showErrors = true;
                    
                    for (const field in this.formData) {
                        if (!this.isFieldValid(field)) {
                            console.log(`Validation failed for ${field}`);
                            return;
                        }
                    }
                    
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

                    // Debugging: Log all form data entries
                    // console.log("Final FormData:");
                    // for (let [key, value] of formData.entries()) {
                    //     console.log(`${key}: ${value}`);
                    // }


                    // const methodtype = 'POST';
                    
                    // ajaxCall(url,methodtype, [[onSuccess, ['response']]], formData);
                },
                
            };

        }
    </script>
@endsection