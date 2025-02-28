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
        <style>
            .disabled\:bg-disable:disabled{
                background-color: rgb(229 231 235 / var(--tw-bg-opacity)) !important;
            }
            #job_code{
                background-color: rgb(229 231 235 / var(--tw-bg-opacity)) !important;
            }
        </style>
        <script>
            var sessionrole = "{{ $sessionrole }}";
        </script>
        <div class="bg-white mx-4 my-8 rounded p-8" x-data='quickcreate({!! json_encode($careerOpportunity, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}, {{ $editIndex ?? "null" }})' x-init="mounted()">
            <form @submit.prevent="submitForm" id="quickaddjob" method="POST">
                <div>
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Basic Information</h3>
                        <div class="flex space-x-4 mt-4">
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
                                        ['profile_worker_type_id', 11],
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

                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label for="jobTitleEmailSignature" class="block mb-2">Job Title for Email Signature</label>
                                <div class="relative">
                                    <input type="text"  x-ref="jobTitleEmailSignature" x-model="formData.jobTitleEmailSignature" id="jobTitleEmailSignature"
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
                            <div class="hidden-fields"> 
                                <input type="hidden" name="min_bill_rate" x-model="formData.billRate"
                                    value="10"
                                    id="billRate" />
                                <input type="hidden" name="max_bill_rate" x-model="formData.maxBillRate"
                                    value="20"
                                    id="maxBillRate" />
                            
                                <input type="hidden" name="payment_type" x-model="formData.payment_type"
                                    value="35"
                                    id="payment_type" />
                                
                                <input type="hidden" name="type_of_job" x-model="formData.timeType"
                                    value="38"
                                    id="timeType" />
                                
                                <input type="hidden" name="currency" x-model="formData.currency"
                                    value="2"
                                    id="currency" />
                                    
                                <input id="regular_cost" type="hidden" x-model="formData.regularCost" />

                                <input id="single_resource_cost" type="hidden" x-model="formData.singleResourceCost" />
                                
                                <input id="all_resources_span" type="hidden" x-model="formData.allResourcesRegularCost" />

                                <input id="all_resources_input" type="hidden" x-model="formData.allResourcesCost" />

                                <input id="regular_hours" type="hidden" value="8" x-model="formData.regularHours" />

                                <input id="numOfWeeks" type="hidden" x-model="formData.numberOfWeeks" value="0 Weeks 1 Days" />
                            </div>
                    </div> 
                    
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Additional Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">Business Unit <span class="text-red-500">*</span></label>
                                <select name="bu_id" x-ref="businessUnitSelect" x-model="formData.businessUnit"
                                    class="w-full select2-single custom-style businessUnitSel" data-field="businessUnit">
                                    <option value="">Select Business Unit</option>
                                    @foreach (getActiveRecordsByType('busines-unit') as $record)
                                    <option value="{{ $record->id }}">{{ $record->name }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('businessUnit')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('businessUnit')"></p>
                            </div>
                            

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
                        </div>

                        <div class="flex space-x-4 mt-4">
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

                            <div class="flex-1">
                                <label class="block mb-2">Worker Type <span class="text-red-500">*</span></label>
                                <select name="worker_type_id" id="Job_worker_type" x-model="formData.workerType"
                                    class="w-full select2-single custom-style" data-field="workerType">
                                    <option value="default">Default Worker Type</option>
                                    @foreach (checksetting(3) as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('workerType')" class="text-red-500 text-sm mt-1"
                                x-text="getErrorMessageById('workerType')"></p>
                            </div>
                        </div>
                    </div>

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Job Duration</h3>
                        <div class="flex space-x-4 mt-4">
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
                    </div>

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Notes</h3>
                        <div class="mt-4">
                            <div class="mt-4 block">
                                <label class="block mb-2">Additional Requirements
                                    <!-- <span class="text-red-500">*</span> -->
                                </label>
                                <div id="additionalRequirementEditor" style="height: 300px"></div>
                                <!-- <p x-show="showErrors && !isFieldValid('additionalRequirementEditor')"
                                    class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('additionalRequirementEditor')">
                                </p> -->
                            </div>

                            <div class="mt-4 block">
                                <label class="block mb-2">Business Justification
                                    <span class="text-red-500">*</span></label>
                                <div id="buJustification" style="height: 300px"></div>
                                <p x-show="showErrors && !isFieldValid('buJustification')"
                                    class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('buJustification')">
                                </p>
                            </div>
                        </div>

                        <div class="block mt-4">
                            <div class="block">
                                <label class="block mb-2">Has Corporate or Divisional Legal reviewed the contract to ensure it supports onboarding workers?
                                    <span class="text-red-500">*</span></label>
                                <select name="corporate_legal" x-model="formData.corporate_legal"
                                    class="w-full select2-single custom-style" data-field="corporate_legal" id="corporate_legal">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                                <p x-show="showErrors && !isFieldValid('corporate_legal')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('corporate_legal')"></p>
                            </div>

                            <!-- Conditionally Displayed Input -->
                            <div class="block mt-4" x-show="formData.corporate_legal === 'Yes'">
                                <label for="expectedCost" class="block mb-2">Expected Total Cost of Engagement
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="expectedCost" name="expectedCost" min="0" 
                                        x-model="formData.expectedCost"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                        placeholder="Enter total cost" />
                                </div>
                                <p x-show="showErrors && !isFieldValid('expectedCost')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('expectedCost')"></p>
                            </div>

                            <div class="block mt-2" x-show="formData.corporate_legal === 'No'">
                                <p class="text-red-500 text-sm mt-1">Please work with legal, or procurement if the engagement is more than $50k to ensure the contract supports onboarding workers before continuing.</p>
                            </div>
                            
                        </div>

                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <input type="checkbox" id="acknowledgement" x-model="formData.acknowledgement" 
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                    <label for="acknowledgement" class="ml-3 text-gray-700">
                                        I understand that any employee who misrepresents any of the information above in efforts to bypass the formal contracting and vetting process will be subject to discipline, up to and including termination of employment.
                                        <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                <p x-show="showErrors && !isFieldValid('acknowledgement')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('acknowledgement')"></p>
                            </div>
                        </div>

                        
                    </div>

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Other Details</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">Estimated Hours / Day
                                    <span class="text-red-500">*</span></label>
                                <input name="hours_per_day" type="number" x-model="formData.estimatedHoursPerDay"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                    min="0" step="0.5" max="24" id="hours_per_day" x-on:change="calculateRate()"/>
                                <p x-show="showErrors && !isFieldValid('estimatedHoursPerDay')"
                                    class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('estimatedHoursPerDay')"></p>
                            </div>
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
                        </div>
                    </div>
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Pre Identified Candidate</h3>
                        <div class="flex space-x-4 mt-4">
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
                        <div class="mt-4">
                            <div class="flex space-x-4 mb-4">
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate First Name
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="pre_name" x-model="formData.candidateFirstName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none disabled:bg-disable"
                                        placeholder="Enter first name" id="candidateFirstName"  :disabled="formData.preIdentifiedCandidate !== 'Yes'"/>
                                    <p x-show="showErrors && !isFieldValid('candidateFirstName')"
                                        class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('candidateFirstName')">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Middle Name</label>
                                    <input name="pre_middle_name" type="text" x-model="formData.candidateMiddleName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none disabled:bg-disable"
                                        placeholder="Enter middle name" :disabled="formData.preIdentifiedCandidate !== 'Yes'" />
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Last Name
                                        <span class="text-red-500">*</span></label>
                                    <input name="pre_last_name" type="text" x-model="formData.candidateLastName"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none disabled:bg-disable"
                                        placeholder="Enter last name" id="candidateLastName" :disabled="formData.preIdentifiedCandidate !== 'Yes'"/>
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
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none disabled:bg-disable"
                                        placeholder="(XXXX) XXX-XXXX" id="candidatePhone" :disabled="formData.preIdentifiedCandidate !== 'Yes'"/>
                                    <p x-show="showErrors && !isFieldValid('candidatePhone')" class="text-red-500 text-sm mt-1"
                                        x-text="getErrorMessageById('candidatePhone')"></p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Candidate Email
                                        <span class="text-red-500">*</span></label>
                                    <input name="candidate_email" type="email" x-model="formData.candidateEmail"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none disabled:bg-disable"
                                        placeholder="Enter email" id="candidateEmail" :disabled="formData.preIdentifiedCandidate !== 'Yes'"/>
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
        function quickcreate(careerOpportunity = null, editIndex) {
            console.log(careerOpportunity);
            return {
                careerOpportunity,
                editIndex,
                showErrors: false,
                
                
                formData: {
                    jobLaborCategory: careerOpportunity?.cat_id || "",
                    jobTitle: careerOpportunity?.template_id || "",
                    job_code:careerOpportunity?.job_code || "",
                    hiringManager: careerOpportunity?.hiring_manager || "",
                    jobLevel: careerOpportunity?.job_level || "",
                    workLocation: careerOpportunity?.location_id || "",
                    workLocation: careerOpportunity?.location_id || "",
                    virtualRemote: careerOpportunity?.remote_option || "",
                    division: careerOpportunity?.division_id || "",
                    regionZone: careerOpportunity?.region_zone_id || "",
                    branch: careerOpportunity?.branch_id || "",
                    workerType: careerOpportunity?.worker_type_id || "",
                    startDate: careerOpportunity?.start_date || "",
                    endDate: careerOpportunity?.end_date || "",
                    additionalRequirementEditor: careerOpportunity?.internal_notes || "",
                    buJustification: careerOpportunity?.description || "",
                    corporate_legal: careerOpportunity?.expected_cost ? "Yes" : "",
                    expectedCost: careerOpportunity?.expected_cost || "",
                    acknowledgement: careerOpportunity?.expected_cost ? true : false,
                    estimatedHoursPerDay: careerOpportunity?.hours_per_day || "",
                    workDaysPerWeek: careerOpportunity?.day_per_week || "",
                    numberOfPositions: careerOpportunity?.num_openings || "",
                    preIdentifiedCandidate: careerOpportunity?.pre_candidate || "",
                    candidateFirstName:careerOpportunity?.pre_name || "",
                    candidateMiddleName: "",
                    candidateLastName: careerOpportunity?.pre_last_name || "",
                    candidatePhone: careerOpportunity?.candidate_phone || "",
                    candidateEmail: careerOpportunity?.candidate_email || "",
                    jobTitleEmailSignature: careerOpportunity?.alternative_job_title || "",
                    timeType: 38,
                    businessUnit: "{{ $buId ?? null }}",
                    billRate: 10,
                    maxBillRate: 20,
                    payment_type: 35,
                    currency: 2,
                    regularCost: "",
                    singleResourceCost: "",
                    allResourcesRegularCost: "",
                    allResourcesCost: "",
                    regularHours: "",
                    numberOfWeeks: "",
                },

                isFieldValid(fieldId) {
                    const fieldValue = this.formData[fieldId];
                    
                    if (['candidateMiddleName', 'jobTitleEmailSignature', 'additionalRequirementEditor'].includes(fieldId)) {
                        return true; 
                    }
                    if (fieldId === 'candidateFirstName' || fieldId === 'candidateLastName' || fieldId === 'candidatePhone' || fieldId === 'candidateEmail') {
                        if (this.formData.preIdentifiedCandidate !== 'Yes') {
                            return true;
                        }
                    }
                    if (fieldId === 'acknowledgement') {
                        // For checkboxes, check if the value is true (checked)
                        return fieldValue === true; 
                    } else if (fieldId === 'buJustification') {
                        // Validate buJustification content
                        const editorContent = this.formData.buJustification.trim();
                        return editorContent !== "" && editorContent !== "<p><br></p>";
                    } else if (fieldValue === null || fieldValue === undefined) {
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
                        jobLaborCategory: 'Job Labor Category is required.',
                        jobTitle: 'Job Title is required.',
                        hiringManager: 'Hiring Manager is required.',
                        jobLevel: 'Job Level is required.',
                        workLocation: 'Work Location is required.',
                        virtualRemote: 'Virtual Candidate is required.',
                        businessUnit: 'Business Unit is required.',
                        division: 'Division is required.',
                        regionZone: 'Region/Zone is required.',
                        branch: 'Branch is required.',
                        workerType: 'Worker Type is required.',
                        startDate: 'Start Date is required.',
                        endDate: 'End Date is required.',
                        additionalRequirementEditor: 'Additional Requirement is required.',
                        buJustification: 'Business Justification is required.',
                        corporate_legal: 'Corporate Legal is required.',
                        expectedCost: 'Expected Cost is required.',
                        acknowledgement: 'Acknowledgement is required.',
                        estimatedHoursPerDay: 'Estimated Hours per Day is required.',
                        workDaysPerWeek: 'Work Days per Week is required.',
                        numberOfPositions: 'Number of Positions is required.',
                        preIdentifiedCandidate: 'Pre-Identified Candidate is required.',
                        candidateFirstName: 'Candidate First Name is required.',
                        candidateLastName: 'Candidate Last Name is required.',
                        candidatePhone: 'Candidate Phone is required.',
                        candidateEmail: 'Candidate Email is required.',
                    };
                    return errorMessages[fieldId] || 'This field is required.';
                },

                init() {
                    
                    this.initSelect2();
                    this.initQuill([
                        '#additionalRequirementEditor',
                        '#buJustification'
                    ]);
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
                    flatpickr("#startDate", {
                        dateFormat: "m/d/Y",
                        defaultDate: this.formData.startDate || null,
                        onChange: (selectedDates, dateStr) => {
                        this.formData.startDate = dateStr;
                        this.endDatePicker.set("minDate", dateStr);
                        },
                    });

                    this.endDatePicker = flatpickr("#endDate", {
                        dateFormat: "m/d/Y",
                        defaultDate: this.formData.endDate || null,
                        onChange: (selectedDates, dateStr) => {
                        this.formData.endDate = dateStr;
                        },
                    });
                },
                initQuill(selectors) {
                    selectors.forEach((selector) => {
                        if (document.querySelector(selector).classList.contains('ql-container')) {
                            return;
                        }

                        var quill = new Quill(selector, {
                            theme: 'snow'
                        });

                        // Set initial content if available
                        if (this.formData[selector.slice(1)]) {
                            quill.root.innerHTML = this.formData[selector.slice(1)];
                        }

                        // Update formData on content change
                        quill.on('text-change', () => {
                            this.formData[selector.slice(1)] = quill.root.innerHTML;
                        });
                    });
                },

                mounted() {
                    console.log(window.$); // Verify jQuery is available
                    if (window.$) {
                        $('#jobLaborCategory').on('change', () => {
                            var labour_type = $('#jobLaborCategory').val();
                            var type = 11;
                            let url = `/load-market-job-template/${labour_type}/${type}`;

                            ajaxCall(url, 'GET', [[updateStatesDropdown, ['response', 'jobTitle']]]);
                        });

                        $('#jobTitle, #jobLevel').on('change', () => {
                            this.loadBillRate();
                            this.loadTemplate();
                        });

                        this.loadBillRate = () => {
                            var level_id = $('#jobLevel').find(':selected').val();
                            var template_id = $('#jobTitle').find(':selected').val();

                            $('#maxBillRate').val('');
                            $('#billRate').val('');
                            
                            let url = `/load-job-template`;

                            if (level_id != '' && template_id != '') {
                                let data = new FormData();
                                data.append('template_id', template_id);
                                data.append('level_id', level_id);
                                const updates = {
                                '#maxBillRate': { type: 'value', field: 'max_bill_rate' },
                                '#billRate': { type: 'value', field: 'min_bill_rate' },
                                '#currency': { type: 'select2', field: 'currency' },
                                // '#currency': { type: 'value', field: 'currency_class' },
                                // Add more mappings as needed
                                };
                                ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
                                setTimeout(() => {
                                this.formData.billRate =  $('#billRate').val();
                                this.formData.maxBillRate = $('#maxBillRate').val();
                                this.formData.currency = $('#currency').val();
                                }, 1000);
                            }
                        };

                        this.loadTemplate = () => {
                            var template_id = $('#jobTitle').find(':selected').val();

                            let url = `/load-job-template/`;
                            let data = new FormData();
                            data.append('template_id', template_id);
                            const updates = {
                                // '#jobDescriptionEditor': { type: 'quill', field: 'job_description' },
                                // '#job_family_value': { type: 'value', field: 'job_family_id' },
                                // '#Job_worker_type': { type: 'disabled', value: true },
                                '#Job_worker_type': { type: 'select2', field: 'worker_type' },
                                // '#worker_type_value': { type: 'value', field: 'worker_type' },
                                '#job_code': { type: 'value', field: 'job_code' },
                                // Add more mappings as needed
                            };
                            ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
                            setTimeout(() => {
                                // this.formData.jobDescriptionEditor =  $('#jobDescriptionEditor').val();
                                this.formData.workerType = $('#Job_worker_type').val();
                                this.formData.job_code = $('#job_code').val();
                            }, 500);
                        };
                        
                        $('.businessUnitSel').on('change', () => {
                         this.selBU();
                        });
                        
                        this.calculateRate = () => {
                            console.log('calculateRate');
                            var bill_rate =  $('#billRate').val();
                            var payment_type =  $('#payment_type').val();
                            var hours_per_week = $('#workDaysPerWeek').val();
                            var Job_start_date = $('#startDate').val();
                            var Job_end_date = $('#endDate').val();
                            var openings = $("#num_openings").val();
                            var hours_per_day = $("#hours_per_day").val();

                            $("#job_duration").html(Job_start_date + ' - ' + Job_end_date);
                            $("#job_duration1").html(Job_start_date + ' - ' + Job_end_date);
                            // console.log('hours_per_day', hours_per_day);

                            var sumOfEstimates = 0;
                            $('.addCost').each(function() {
                                var addedValue = $(this).val().replace(/,/g, '');
                                sumOfEstimates += (isNaN(parseFloat(addedValue))) ? 0.00 : parseFloat(addedValue);
                            });
                            let data = new FormData();
                            data.append('bill_rate', bill_rate);
                            data.append('other_amount_sum', sumOfEstimates);
                            data.append('payment_type', payment_type);
                            data.append('start_date', Job_start_date);
                            data.append('end_date', Job_end_date);
                            data.append('opening', openings);
                            data.append('hours_per_day', hours_per_day);
                            data.append('days_per_week', hours_per_week);
                                let url;
                                if (sessionrole === 'admin') {
                                    url = `/admin/job-rates/`;
                                } else if (sessionrole === 'client') {
                                    url = `/client/job-rates/`;
                                }
                                else if (sessionrole === 'vendor') {
                                    url = `/vendor/job-rates/`;
                                }
                                else if (sessionrole === 'consultant') {
                                    url = `/consultant/job-rates/`;
                                }
                                /*          let url = `/admin/job-rates/`;*/
                            const updates = {
                                '#regular_cost': { type: 'value', field: 'regularBillRate' },
                                '#single_resource_cost': { type: 'value', field: 'singleResourceCost' },
                                '#all_resources_span': { type: 'value', field: 'regularBillRateAll'},
                                '#all_resources_input': { type: 'value', field: 'allResourceCost' },
                                '#regular_hours': { type: 'value', field: 'totalHours'},
                                '#numOfWeeks': { type: 'value', field: 'numOfWeeks' }
                            };
                            ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
                            setTimeout(() => {
                                this.formData.regularCost =  $('#regular_cost').val();
                                this.formData.singleResourceCost = $('#single_resource_cost').val();
                                this.formData.allResourcesRegularCost = $('#all_resources_span').val();
                                this.formData.regularHours =  $('#regular_hours').val();
                                this.formData.allResourcesCost = $('#all_resources_input').val();
                                this.formData.numberOfWeeks = $('#numOfWeeks').val();
                            }, 500);
                        };

                        
                        this.init();

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
                    // console.log('Form Data:', this.formData.bill_rate);
                    for (const field in this.formData) {
                        if (!this.isFieldValid(field)) {
                            console.log(`Validation failed for ${field}`);
                            return;
                        }
                    }
                    
                    if (this.formData.corporate_legal === 'No') {
                        console.log(`Validation failed for corporate legal`);
                        return;
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

                    formData.append("jobTitleEmailSignature", this.formData.jobTitleEmailSignature);

                    // Debugging: Log all form data entries
                    // console.log("Final FormData:");
                    // for (let [key, value] of formData.entries()) {
                    //     console.log(`${key}: ${value}`);
                    // }
                    
                    const methodtype = 'POST';
                    const url = '{{ isset($editIndex) ? "/admin/quickjob-update/" . $editIndex : "/admin/quickjob-store" }}';
                    ajaxCall(url,methodtype, [[onSuccess, ['response']]], formData);
                    
                },

                selBU() {
                    
                    this.$nextTick(() => {
                        
                        // console.log('Selected Business Unit ID:', this.formData.businessUnit);
                       
                        let url = `/division-load`;
                        let data = new FormData();
                        data.append('bu_id', this.formData.businessUnit);

                        const updates = {
                            '#regionZone': { type: 'select2append', field: 'zone' },
                            '#branch': { type: 'select2append', field: 'branch' },
                            '#division': { type: 'select2append', field: 'division' },
                            // '#currency': { type: 'value', field: 'currency_class' },
                            // Add more mappings as needed
                        };
                        
                        ajaxCall(url,  'POST', [[updateElements, ['response', updates]]], data);
                        
                    });
                },

            };
        }
    </script>
@endsection