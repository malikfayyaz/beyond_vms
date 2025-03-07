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
        <style>
            #hireManager{
                background-color: rgb(229 231 235 / var(--tw-bg-opacity)) !important;
            }
        </style>

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
                                <input x-ref="hireManager" name="hiring_manager" x-model="formData.hireManager"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" data-field="hireManager" id="hireManager" disabled>
                            
                                <p x-show="showErrors && !isFieldValid('hireManager')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('hireManager')"></p>
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
                                    <label class="block mb-2">Date of Birth (MM/DD)
                                        <span class="text-red-500">*</span></label>
                                    <input name="candidateDob" id="candidateDob"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                        x-ref="candidateDob" type="text" x-model="formData.candidateDob"
                                        placeholder="Select Candidate DOB" />
                                    <p x-show="showErrors && !isFieldValid('candidateDob')" class="text-red-500 text-sm mt-1"
                                        x-text="getErrorMessageById('candidateDob')"></p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Last 4 Numbers of National ID
                                        <span class="text-red-500">*</span></label>
                                    <input name="candidateNatNum" id="candidateNatNum"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                        x-ref="candidateNatNum" type="num" x-model="formData.candidateNatNum"
                                        placeholder="Enter Last 4 Digits" 
                                        maxlength="4"
                                        pattern="\d{4}" 
                                        @input="formData.candidateNatNum = formData.candidateNatNum.replace(/\D/g, '').slice(0, 4)" />
                                    <p x-show="showErrors && !isFieldValid('candidateNatNum')" class="text-red-500 text-sm mt-1"
                                        x-text="getErrorMessageById('candidateNatNum')"></p>
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
                    hireManager: '',
                    hireManagerId: '',
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
                    candidateDob: '',
                    candidateNatNum: '',
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
                       
                        if (['candidateFirstName', 'candidateLastName', 'candidateDob', 'candidateNatNum','candidateEmail'].includes(fieldId)) {
                            return fieldValue && fieldValue.trim() !== "";
                        }
                        
                        if (fieldId === 'existingCandidate') {
                            return true;
                        }
                    } else if (this.formData.newExist === "2") {
                        this.formData.candidateFirstName = "";
                        this.formData.candidateMiddleName = "";
                        this.formData.candidateLastName = "";
                        this.formData.candidateDob = "";
                        this.formData.candidateNatNum = "";
                        this.formData.candidateEmail = "";
                       
                        if (['existingCandidate'].includes(fieldId)) {
                            return fieldValue && fieldValue.trim() !== "";
                        }

                        if (['candidateFirstName', 'candidateLastName', 'candidateDob', 'candidateNatNum','candidateEmail'].includes(fieldId)) {
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
                        hireManager: 'Please select a hiring manager',
                        workLocation: 'Please select a work location',
                        vendor: 'Please select a vendor',
                        subDate: 'Please select a submission date',
                        offStartDate: 'Please select a start date',
                        offEndDate: 'Please select an end date',
                        newExist: 'Please select new/existing candidate',
                        phyLocation: 'Please select a physical work location',
                        candidateFirstName: 'Please enter candidate first name',
                        candidateLastName: 'Please enter candidate last name',
                        candidateDob: 'Please select candidate date of birth',
                        candidateNatNum: 'Please enter last 4 digits of national ID',
                        candidateEmail: 'Please enter candidate email',
                        existingCandidate: 'Please select an existing candidate',
                    };
                    return errorMessages[fieldId] || 'This field is required.';
                },

                init() {
                    this.initSelect2();
                    this.initFlatpickr();
                    this.initJobProfileChangeListener();
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
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "m/d/Y",
                        defaultDate: this.formData.subDate,
                        onChange: (selectedDates, dateStr, instance) => {
                            this.formData.subDate = dateStr;
                        },
                    });

                    flatpickr("#candidateDob", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "m/d",
                        defaultDate: this.formData.candidateDob,
                        onReady: function (selectedDates, dateStr, instance) {
                            instance.currentYearElement.style.display = "none"; // Hide year selector
                        },
                        onChange: (selectedDates, dateStr, instance) => {
                            this.formData.candidateDob = dateStr;
                        },
                    });

                    flatpickr("#offStartDate", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "m/d/Y",
                        defaultDate: this.formData.offStartDate,
                        onChange: (selectedDates, dateStr, instance) => {
                            this.formData.offStartDate = dateStr;
                            this.offEndDate.set("minDate", dateStr);
                        },
                    });

                    this.offEndDate = flatpickr("#offEndDate", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "m/d/Y",
                        defaultDate: this.formData.offEndDate || null,
                        onChange: (selectedDates, dateStr) => {
                        this.formData.offEndDate = dateStr;
                        },
                    });
                },

                initJobProfileChangeListener() {
                    $('#jobProfile').on('change', () => {
                        this.updateHiringManager();
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

                updateHiringManager() {
                    const jobProfileId = $('#jobProfile').val();
                    if (jobProfileId) {
                        const url = `/admin/get-hiring-manager/${jobProfileId}`;
                        

                        ajaxCall(url, 'GET', [[this.updateManager.bind(this), ['response', 'hiringManager']]]);
                    } else {
                        this.formData.hireManager = ""; // Clear if no job profile selected
                    }
                },
                updateManager(response) {
                    if (response.success && response.hiringManager) {
                        const manager = response.hiringManager;
                        this.formData.hireManagerId = manager.id;
                        this.formData.hireManager = `${manager.first_name} ${manager.middle_name ?? ''} ${manager.last_name}`.trim();
                    } else {
                        this.formData.hireManager = ""; // Clear if no valid response
                    }
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
                        formData.append(key, this.formData[key]);
                    });

                    // Debugging: Log all form data entries
                    // console.log("Final FormData:");
                    // for (let [key, value] of formData.entries()) {
                    //     console.log(`${key}: ${value}`);
                    // }

                    const methodtype = 'POST';
                    const url ="{{ route('admin.contract.qs') }}";

                    ajaxCall(url,methodtype, [[onSuccess, ['response']]], formData);
                },
                
            };

        }
    </script>
@endsection