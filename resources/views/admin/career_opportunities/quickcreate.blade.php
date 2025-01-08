@extends('admin.layouts.app')
@vite([ 'resources/js/job/job.js'])
@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data='wizardForm()' x-init="mounted()">
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
                            
                        
                    </div> 
                    
                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Additional Information</h3>
                        <div class="flex space-x-4 mt-4">
                            <div class="flex-1">
                                <label class="block mb-2">Business Unit <span class="text-red-500">*</span></label>
                                <select name="bu_id" x-ref="businessUnit" x-model="formData.businessUnit"
                                    class="w-full select2-single custom-style" data-field="businessUnit">
                                    <option value="">Select Business Unit</option>
                                    @foreach (getActiveRecordsByType('busines-unit') as $record)
                                    <option value="{{ $record->id }}">{{ $record->name }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isBusinessUnitValid" class="text-red-500 text-sm mt-1">
                                    <span x-text="businessUnitErrorMessage"></span>
                                </p>
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
                                    <span class="text-red-500">*</span></label>
                                <div id="additionalRequirementEditor" style="height: 300px"></div>
                                <p x-show="showErrors && !isFieldValid('additionalRequirementEditor')"
                                    class="text-red-500 text-sm mt-1" x-text="getErrorMessageById('additionalRequirementEditor')">
                                </p>
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
                                    min="0" step="0.5" id="hours_per_day" x-on:change="calculateRate()"/>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Submit
                    </button>
                </div>
            </form>
        </div>  
    </div>
@endsection