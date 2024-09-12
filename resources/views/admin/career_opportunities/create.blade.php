@extends('admin.layouts.app')
@vite([ 'resources/js/job/job.js'])
@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8" x-data='wizardForm({!! json_encode($careerOpportunity) !!},{!! json_encode($businessUnitsData) !!})' x-init="mounted()">
    
    <!-- Success Notification -->
        @include('admin.layouts.partials.alerts')
        <!-- Include the partial view -->
        <!-- <div
           x-show="showSuccessMessage"
           class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"
         >
           Job is Added Successfully.
         </div> -->
        <!-- Progress bar -->
        <div class="mb-8">
            <div class="flex mb-2">
                <nav aria-label="Progress" class="w-full">
                    <ol role="list" class="flex w-full items-center border border-gray-300">
                        <template x-for="(step, index) in steps" :key="index">
                            <li class="relative flex-1 flex items-center">
                                <div class="group flex items-center w-full" :class="{
                       'cursor-pointer': !formSubmitted && index + 1 <= highestStepReached,
                       'cursor-not-allowed': formSubmitted || index + 1 > highestStepReached
                     }" @click="!formSubmitted && index + 1 <= highestStepReached && goToStep(index + 1)">
                                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                                        <span
                                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                                            :class="{
                           'bg-blue-600 group-hover:bg-blue-800': currentStep > index + 1,
                           'border-2 border-blue-600': currentStep === index + 1,
                           'border-2 border-gray-300': currentStep < index + 1
                         }">
                                            <!-- Check icon for completed steps -->
                                            <svg x-show="currentStep > index + 1" class="h-6 w-6 text-white" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>

                                            <!-- Current or future step number -->
                                            <span x-show="currentStep <= index + 1"
                                                :class="currentStep === index + 1 ? 'text-blue-600' : 'text-gray-500'"
                                                x-text="index + 1"></span>
                                        </span>
                                        <span class="ml-4 text-sm font-medium" :class="{
                           'text-blue-600': currentStep > index + 1,
                           'text-gray-900': currentStep === index + 1,
                           'text-gray-500': currentStep < index + 1
                         }" x-text="step"></span>
                                    </span>
                                </div>
                                <div x-show="index !== steps.length - 1"
                                    class="absolute top-0 right-0 h-full flex items-center" aria-hidden="true">
                                    <svg class="h-full w-5" :class="{
                         'text-blue-600': currentStep > index + 1,
                         'text-gray-300': currentStep <= index + 1
                       }" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
                                        <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke"
                                            stroke="currentcolor" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </li>
                        </template>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Wizard form steps -->
        <form @submit.prevent="submitForm" id="addjobformwizard" method="POST" enctype="multipart/form-data">
         @if(isset($careerOpportunity->id))
                @method('PUT')
                @endif    
        <!-- Step 1: Basic Info -->
            <div x-show="currentStep === 1">
                <h2 class="text-2xl font-bold mb-4">Basic Information</h2>
                <!-- Step 1: First row form fields -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block mb-2">Job Labor Category
                            <span class="text-red-500">*</span></label>
                        <select x-ref="jobLaborCategory" name="cat_id" x-model="formData.jobLaborCategory"
                            class="w-full select2-single custom-style" data-field="jobLaborCategory"
                            id="jobLaborCategory">
                            <option value="">Select a category</option>
                            @foreach (checksetting(5) as $key => $value)
                            <option value="javascript">JavaScript</option>
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach

                        </select>
                        <p x-show="showErrors && !isFieldValid('jobLaborCategory')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobLaborCategory')"></p>
                    </div>
                    @php
                        $jobTemplates = [];
                        if ($careerOpportunity !="") {
                            $jobTemplates = \App\Models\JobTemplates::where([
                                ['cat_id', $careerOpportunity->cat_id],
                                ['profile_worker_type_id', 10],
                                ['status', 'Active']
                            ])->get(['id', 'job_title']);
                        }
                    @endphp
                    <div class="flex-1">
                        <label class="block mb-2">Job Title <span class="text-red-500">*</span></label>
                        <select x-ref="jobTitle" name="title" x-model="formData.jobTitle"
                            class="w-full select2-single custom-style" data-field="jobTitle" id="jobTitle">
                            <option value="">Select a job title</option>
                            @foreach($jobTemplates as $template)
                                <option value="{{ $template->id }}" >
                                    {{ $template->job_title }}
                                </option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('jobTitle')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobTitle')"></p>
                    </div>
                    <div class="flex-1">
                        <label for="disabledInput" class="block mb-2">Job Code</label>
                        <div class="relative">
                            <input type="text" id="job_code"  x-ref="job_code" name="job_code" x-model="formData.job_code" disabled
                                class="w-full h-12 px-4 bg-gray-100 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none cursor-not-allowed appearance-none" />
                        </div>
                    </div>
                </div>
                <!-- Step 1: Second row form fields -->
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label for="jobTitleEmailSignature" class="block mb-2">Job Title for Email Signature</label>
                        <div class="relative">
                            <input type="text"  x-ref="jobTitleEmailSignature" name="alternative_job_title" x-model="formData.jobTitleEmailSignature" id="jobTitleEmailSignature"
                                name="jobTitleEmailSignature"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" />
                        </div>
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
                        <label class="block mb-2">Job Level <span class="text-red-500">*</span></label>
                        <select x-ref="jobLevel" name="job_level" x-model="formData.jobLevel"
                            class="w-full select2-single custom-style" data-field="jobLevel" id="jobLevel">
                            <option value="">Select a job title</option>
                            @foreach (checksetting(1) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('jobLevel')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobLevel')"></p>
                    </div>
                </div>
                <!-- Step 1: Third row form fields -->
                <div class="flex space-x-4 mt-4">
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
                    <div class="flex-1">
                        <label class="block mb-2">Currency <span class="text-red-500">*</span></label>
                        <select x-ref="currency" name="currency_id" x-model="formData.currency"
                            class="w-full select2-single custom-style" data-field="currency" id="currency">
                            <option value="">Select a Currency</option>
                            @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->setting->title }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('currency')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('currency')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">
                            Minimum Bill Rate <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="text" name="min_bill_rate" x-model="formData.billRate"
                                    x-on:input="formatBillRate($event.target.value)"
                                    x-on:focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    placeholder="0.00" id="billRate" x-on:change="calculateRate()" />
                            </div>
                            <p x-show="showErrors && !isFieldValid('billRate')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('billRate')"></p>
                        </div>
                    </div>
                </div>
                <!-- Step 1: Fourth row form fields -->
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block mb-2">
                            Maximum Bill Rate <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input type="text" name="max_bill_rate" x-model="formData.maxBillRate"
                                    x-on:input="formatMaxBillRate($event.target.value)"
                                    x-on:focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    placeholder="0.00" id="maxBillRate" x-on:change="calculateRate()" />
                            </div>
                            <p x-show="showErrors && !isFieldValid('maxBillRate')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('maxBillRate')"></p>
                        </div>
                    </div>
                    <div class="flex-1"></div>
                    <div class="flex-1"></div>
                </div>
            </div>
            <!-- Step 2: Duration and Description -->
            <div x-show="currentStep === 2">
                <h2 class="text-2xl font-bold mb-4">Duration & Description</h2>
                <!-- Step 2: First row form fields -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block mb-2">Pre-Identified Candidate?
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="pre_candidate" x-ref="preIdentifiedCandidate"
                            x-model="formData.preIdentifiedCandidate" class="w-full select2-single custom-style"
                            data-field="preIdentifiedCandidate" id="preIdentifiedCandidate">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('preIdentifiedCandidate')"
                            class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('preIdentifiedCandidate')">
                        </p>
                    </div>
                    <div class="flex-1"></div>
                    <div class="flex-1"></div>
                </div>
                <!-- Step 2: Pre-Identified Candidate? on "Yes" form fields -->
                <div x-show="formData.preIdentifiedCandidate === 'Yes'" class="mt-4">
                    <div class="flex space-x-4 mb-4">
                        <div class="flex-1">
                            <label class="block mb-2">Candidate First Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="pre_name" x-model="formData.candidateFirstName"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Enter first name" id="candidateFirstName" />
                            <p x-show="showErrors && !isFieldValid('candidateFirstName')"
                                class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('candidateFirstName')">
                            </p>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2">Candidate Middle Name</label>
                            <input name="pre_middle_name" type="text" x-model="formData.candidateMiddleName"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Enter middle name" />
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2">Candidate Last Name
                                <span class="text-red-500">*</span></label>
                            <input name="pre_last_name" type="text" x-model="formData.candidateLastName"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Enter last name" id="candidateLastName" />
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
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="(XXXX) XXX-XXXX" id="candidatePhone" />
                            <p x-show="showErrors && !isFieldValid('candidatePhone')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('candidatePhone')"></p>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2">Candidate Email
                                <span class="text-red-500">*</span></label>
                            <input name="candidate_email" type="email" x-model="formData.candidateEmail"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Enter email" id="candidateEmail" />
                            <p x-show="showErrors && !isFieldValid('candidateEmail')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('candidateEmail')"></p>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2">Worker Pay Rate
                                <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                <input name="pre_current_rate" type="text" x-model="formData.workerPayRate"
                                    x-on:input="formatCost('workerPayRate')"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    placeholder="0.00" id="workerPayRate" />
                            </div>
                            <p x-show="showErrors && !isFieldValid('workerPayRate')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('workerPayRate')"></p>
                        </div>
                    </div>

                </div>
                <!-- Step 2: Second row form fields -->
                <div class="flex space-x-4 mt-4" x-data="{
               startDate: '',
               endDate: '',
               init() {
                   let startPicker = flatpickr(this.$refs.startPicker, {
                       dateFormat: 'Y/m/d',
                       onChange: (selectedDates, dateStr) => {
                         this.formData.startDate = dateStr;
                         endPicker.set('minDate', dateStr);
                       }
                   });
       
                   let endPicker = flatpickr(this.$refs.endPicker, {
                       dateFormat: 'Y/m/d',
                       onChange: (selectedDates, dateStr) => {
                         this.formData.endDate = dateStr;
                       }
                   });
       
                   this.$watch('startDate', value => startPicker.setDate(value));
                   this.$watch('endDate', value => endPicker.setDate(value));
               }
           }">
                    <div class="flex-1">
                        <label class="block mb-2">Labour Type <span class="text-red-500">*</span></label>
                        <select name="labour_type" x-ref="laborType" x-model="formData.laborType"
                            class="w-full select2-single custom-style" data-field="laborType" id="laborType">
                            <option value="">Select a category</option>
                            @foreach (checksetting(6) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('laborType')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('laborType')"></p>
                    </div>
                    <div class="flex-1">
                        <label for="startDate" class="block mb-2">Choose Start Date:
                            <span class="text-red-500">*</span></label>
                        <input name="start_date" id="startDate"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                            x-ref="startPicker" type="text" x-model="formData.startDate"
                            placeholder="Select start date" x-on:change="calculateRate()" />
                        <p x-show="showErrors && !isFieldValid('startDate')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('startDate')"></p>
                    </div>
                    <div class="flex-1">
                        <label for="endDate" class="block mb-2">Choose End Date: <span
                                class="text-red-500">*</span></label>
                        <input name="end_date" id="endDate"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                            x-ref="endPicker" type="text" placeholder="Select end date" x-model="formData.endDate" x-on:change="calculateRate()" />
                        <p x-show="showErrors && !isFieldValid('endDate')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('endDate')"></p>
                    </div>
                </div>
                <!-- Step 2: Third row - Text Editor -->

                <div class="mt-4">
                    <label class="block mb-2">Job Description <span class="text-red-500">*</span></label>
                    <div id="jobDescriptionEditor" style="height: 300px"></div>
                    <p x-show="showErrors && !isFieldValid('jobDescriptionEditor')" class="text-red-500 text-sm mt-1"
                        x-text="getErrorMessageById('jobDescriptionEditor')"></p>
                </div>
                <!-- Step 2: Fourth row - Text Editor -->
                <div class="mt-4">
                    <label class="block mb-2">Qualifications/Skills
                        <span class="text-red-500">*</span></label>
                    <div id="qualificationSkillsEditor" style="height: 300px"></div>
                    <p x-show="showErrors && !isFieldValid('qualificationSkillsEditor')"
                        class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('qualificationSkillsEditor')"></p>
                </div>
                <!-- Step 2: Fifth row - Text Editor -->
                <div class="mt-4">
                    <label class="block mb-2">Additional Requirements
                        <span class="text-red-500">*</span></label>
                    <div id="additionalRequirementEditor" style="height: 300px"></div>
                    <p x-show="showErrors && !isFieldValid('additionalRequirementEditor')"
                        class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('additionalRequirementEditor')">
                    </p>
                </div>
                <!-- Step 2: Sixth row - Attachment -->
                <div class="mt-4">
                    <label for="jobAttachment" class="block text-sm font-medium text-gray-700 mb-2">Job
                        Attachment</label>
                    <input name="attachment" type="file" id="jobAttachment" name="jobAttachment" @change="handleFileUpload"
                        class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                </div>
            </div>
            <!-- Step 3: Additional Information -->
            <div x-show="currentStep === 3">
                <h2 class="text-2xl font-bold mb-4">Additional Information</h2>
                <!-- Step 3: Business Unit and Budget Percentage -->
                <div class="mb-6">
                    <div class="flex space-x-4 mb-4">
                        <div class="flex-1">
                            <label class="block mb-2">Business Unit <span class="text-red-500">*</span></label>
                            <select name="bu_id" x-ref="businessUnitSelect" x-model="selectedBusinessUnit"
                                class="w-full select2-single custom-style" data-field="businessUnit">
                                <option value="">Select Business Unit</option>
                                @foreach (getActiveRecordsByType('busines-unit') as $record)
                                <option value="{{ $record->id }}">{{ $record->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block mb-2">Budget Percentage
                                <span class="text-red-500">*</span></label>
                            <input type="number" x-model="budgetPercentage"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Enter percentage" min="0" max="100" />
                        </div>
                        <div class="flex-1 flex items-end">
                            <button @click="addBusinessUnit" type="button"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                    <p x-show="showErrors && !isBusinessUnitValid" class="text-red-500 text-sm mt-1">
                        <span x-text="businessUnitErrorMessage"></span>
                    </p>

                    <!-- Table for Business Units & Percentage -->
                    <table class="w-full mt-4 border-collapse">
                        <thead>
                            <tr class="bg-blue-500 text-white">
                                <th class="text-left py-2 px-3">BU</th>
                                <th class="text-left py-2 px-3">Budget Percentage</th>
                                <th class="text-center py-2 px-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(bu, index) in formData.businessUnits" :key="index">
                                <tr class="border-b border-gray-200">
                                    <td class="py-2 px-3" x-text="bu.unit"></td>
                                    <td class="py-2 px-3" x-text="bu.percentage + '%'"></td>
                                    <td class="py-2 px-3 text-center">
                                        <button @click="removeBusinessUnit(index)" type="button"
                                            class="text-red-500 hover:text-red-700 transition duration-150 ease-in-out">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <!-- Step 3: Second row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Division <span class="text-red-500">*</span></label>
                        <select name="division_id" x-model="formData.division"
                            class="w-full select2-single custom-style" data-field="division" id="division">
                            <option value="">Select Division</option>
                            @foreach (getActiveRecordsByType('division') as $record)
                                <option value="{{ $record->id }}">{{ $record->name }}</option>
                                @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('division')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('division')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Region/Zone <span class="text-red-500">*</span></label>
                        <select name="region_zone_id" x-model="formData.regionZone"
                            class="w-full select2-single custom-style" data-field="regionZone" id="regionZone">
                            <option value="">Select Region/Zone</option>
                            @foreach (getActiveRecordsByType('region-zone') as $record)
                                <option value="{{ $record->id }}">{{ $record->name }}</option>
                                @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('regionZone')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('regionZone')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Branch <span class="text-red-500">*</span></label>
                        <select name="branch_id" x-model="formData.branch" class="w-full select2-single custom-style"
                            data-field="branch" id="branch">
                            <option value="">Select Branch</option>
                            @foreach (getActiveRecordsByType('branch') as $record)
                                <option value="{{ $record->id }}">{{ $record->name }}</option>
                                @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('branch')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('branch')"></p>
                    </div>
                </div>
                <!-- Step 3: Third row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Expenses Allowed?
                            <span class="text-red-500">*</span></label>
                        <select name="expenses_allowed" x-model="formData.expensesAllowed"
                            class="w-full select2-single custom-style" data-field="expensesAllowed"
                            id="expensesAllowed">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('expensesAllowed')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('expensesAllowed')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Travel Required? <span class="text-red-500">*</span></label>
                        <select name="travel_required" x-model="formData.travelRequired"
                            class="w-full select2-single custom-style" data-field="travelRequired" id="travelRequired">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('travelRequired')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('travelRequired')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">GL Code <span class="text-red-500">*</span></label>
                        <select name="gl_code_id" x-model="formData.glCode" class="w-full select2-single custom-style"
                            data-field="glCode" id="glCode">
                            <option value="">Select GL Code</option>
                            @foreach (getActiveRecordsByType('gl-code') as $record)
                            <option value="{{ $record->id }}">{{ $record->name }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('glCode')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('glCode')"></p>
                    </div>
                </div>
                <!-- Step 3: Fourth row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Sub Ledger Type</label>
                        <select name="ledger_type_id" x-model="formData.subLedgerType"
                            class="w-full select2-single custom-style" data-field="subLedgerType" id="ledger_type">

                            <option value="">Select Sub Ledger Type</option>
                            @foreach (checksetting(7) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                            <!-- Add options here -->
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Sub Ledger Code <span class="text-red-500 ledger_code__"
                                style="display:none;">*</span></label>
                        <input name="ledger_code" type="text" id="ledger_code" x-model="formData.subLedgerCode"
                            class="w-full  h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" />
                            <p x-show="showErrors && !isFieldValid('subLedgerCode')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('subLedgerCode')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Worker Type <span class="text-red-500">*</span></label>
                        <select name="worker_type_id" id="Job_worker_type" x-model="formData.workerType"
                            class="w-full select2-single custom-style" data-field="workerType">
                            <option value="default">Default Worker Type</option>
                            @foreach (checksetting(3) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Step 3: Fifth row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Client Billable? <span class="text-red-500">*</span></label>
                        <select name="client_billable" x-model="formData.clientBillable"
                            class="w-full select2-single custom-style" data-field="clientBillable" id="clientBillable">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('clientBillable')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('clientBillable')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Will this Position Require the Worker to Work OT
                            <span class="text-red-500">*</span></label>
                        <select name="background_check_required" x-model="formData.requireOT"
                            class="w-full select2-single custom-style" data-field="requireOT" id="requireOT">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('requireOT')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('requireOT')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Virtual/Remote Candidate?
                            <span class="text-red-500">*</span></label>
                        <select name="remote_option" x-model="formData.virtualRemote"
                            class="w-full select2-single custom-style" data-field="virtualRemote" id="virtualRemote">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('virtualRemote')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('virtualRemote')"></p>
                    </div>
                </div>
                <!-- Form Fields for Client Name and Estimated Expense -->
                <div class="flex space-x-4 mb-4"
                    x-show="formData.expensesAllowed === 'Yes' || formData.clientBillable === 'Yes'">
                    <div class="flex-1" x-show="formData.clientBillable === 'Yes'">
                        <label class="block mb-2">Client Name <span class="text-red-500">*</span></label>
                        <input name="client_name" type="text" x-model="formData.clientName"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            placeholder="Enter client name" />
                        <p x-show="showErrors && !isFieldValid('clientName')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('clientName')"></p>
                    </div>
                    <div class="flex-1" x-show="formData.expensesAllowed === 'Yes'">
                        <label class="block mb-2">Estimated Expense
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input name="expense_cost" type="text" x-model="formData.estimatedExpense"
                                x-on:input="formatEstimatedExpense($event.target.value)"
                                x-on:focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                placeholder="0.00" id="estimatedExpense" x-on:change="calculateRate()" />
                        </div>
                        <p x-show="showErrors && !isFieldValid('estimatedExpense')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('estimatedExpense')"></p>
                    </div>
                    <div class="flex-1"></div>
                </div>
            </div>
            <!-- Step 4: Other Details -->
            <div x-show="currentStep === 4">
                <h2 class="text-2xl font-bold mb-4">Other Details</h2>
                <!-- Step 4: First row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Payment Type <span class="text-red-500">*</span></label>
                        <select name="payment_type" x-model="formData.payment_type"
                            class="w-full select2-single custom-style" data-field="payment_type" id="payment_type" x-on:change="calculateRate()">
                            <option value="">Select Unit of Measure</option>
                            @foreach (checksetting(8) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('payment_type')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('payment_type')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Time Type <span class="text-red-500">*</span></label>
                        <select name="type_of_job" x-model="formData.timeType"
                            class="w-full select2-single custom-style" data-field="timeType" id="timeType">
                            <option value="">Select Time Type</option>
                            @foreach (checksetting(10) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('timeType')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('timeType')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Estimated Hours / Day
                            <span class="text-red-500">*</span></label>
                        <input name="hours_per_day" type="number" x-model="formData.estimatedHoursPerDay"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            min="0" step="0.5" id="hours_per_day" x-on:change="calculateRate()"/>
                        <p x-show="showErrors && !isFieldValid('estimatedHoursPerDay')"
                            class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('estimatedHoursPerDay')"></p>
                    </div>
                </div>
                <!-- Step 4: Second row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Work Days / Week <span class="text-red-500">*</span></label>
                        <input name="day_per_week" type="number" x-model="formData.workDaysPerWeek"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            min="1" max="7" id="workDaysPerWeek" x-on:change="calculateRate()"/>
                        <p x-show="showErrors && !isFieldValid('workDaysPerWeek')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('workDaysPerWeek')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Number of Positions
                            <span class="text-red-500">*</span></label>
                        <input name="num_openings" id="num_openings" type="number" x-model="formData.numberOfPositions"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            min="1" x-on:change="calculateRate()"/>
                        <p x-show="showErrors && !isFieldValid('numberOfPositions')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('numberOfPositions')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Business Reason <span class="text-red-500">*</span></label>
                        <select name="hire_reason_id" x-model="formData.businessReason"
                            class="w-full select2-single custom-style" data-field="businessReason">
                            <option value="">Select Business Reason</option>
                            @foreach (checksetting(9) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('businessReason')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('businessReason')"></p>
                    </div>
                </div>
                <!-- Budget Details and Other Details -->
                <div class="mb-4">
                    <h3 class="text-2xl font-bold">
                        Budget Details ( Maximum budget is used)
                    </h3>
                    <p class="text-base">
                        Other Details: Duration 08/16/2024 - 08/20/2024
                    </p>
                </div>
                <!-- Step 4: Third row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">Regular Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input id="regular_cost" type="text" x-model="formData.regularCost"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                disabled />
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Single Resource Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input id="single_resource_cost" type="text" x-model="formData.singleResourceCost"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                disabled />
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">All Resources Regular Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input id="all_resources_span" type="text" x-model="formData.allResourcesRegularCost"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                disabled />
                        </div>
                    </div>
                </div>
                <!-- Step 4: Fourth row form fields -->
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2">All Resources Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input id="all_resources_input" type="text" x-model="formData.allResourcesCost"
                                class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                disabled />
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Regular Hours</label>
                        <input id="regular_hours" type="text" x-model="formData.regularHours"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            value="8" disabled />
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">No. Of Weeks</label>
                        <input id="numOfWeeks" type="text" x-model="formData.numberOfWeeks"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            value="0 Weeks 1 Days" disabled />
                    </div>
                </div>
                <!-- Step 4: Accept terms and conditions -->
                <div class="mb-4">
                    <label class="block mb-2" :class="{'text-red-500': showErrors && !formData.termsAccepted}">
                        <input id="termsAccepted" type="checkbox" x-model="formData.termsAccepted" />
                        I accept the terms and conditions
                        <span class="text-red-500">*</span>
                    </label>
                    <p x-show="showErrors && !isFieldValid('termsAccepted')" class="text-red-500 text-sm mt-1"
                        x-text="getErrorMessageById('termsAccepted')"></p>
                </div>
            </div>
            <!-- Navigation buttons -->
            <div class="flex justify-between mt-6">
                <button x-show="currentStep > 1 && !formSubmitted" @click="goToStep(currentStep - 1)" type="button"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Previous
                </button>
                <button x-show="currentStep < 4 && !formSubmitted" @click="nextStep" type="button"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Next
                </button>
                <button x-show="currentStep === 4" type="submit"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

@endsection