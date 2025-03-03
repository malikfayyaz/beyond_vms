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

        <div class="bg-white mx-4 my-8 rounded p-8" x-data='' x-init="mounted()">
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
                                @php $clients_hiring = \App\Models\Vendor::where('profile_status', 1)
                                ->orderBy('first_name', 'ASC')
                                ->get(); @endphp
                                <select x-ref="vendor" name="vendor" x-model="formData.vendor"
                                    class="w-full select2-single custom-style" data-field="hiringManager" id="vendor">
                                    <option value="">Select Vendor</option>
                                    @foreach ($clients_hiring as $key => $value)
                                    <option value="{{ $value->id }}">{{  $value->first_name.' '.$value->last_name; }}</option>
                                    @endforeach
                                </select>
                                <p x-show="showErrors && !isFieldValid('vendor')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('vendor')"></p>
                            </div>
                       
                            <div class="flex-1">
                                <label class="block mb-2">Account Manager <span class="text-red-500">*</span></label>
                                <select x-ref="accManager" name="location_id" x-model="formData.accManager"
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
                                    x-ref="startPicker" type="text" x-model="formData.offStartDate"
                                    placeholder="Select start date" />
                                <p x-show="showErrors && !isFieldValid('offStartDate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('offStartDate')"></p>
                            </div>
                            <div class="flex-1">
                                <label for="offEndDate" class="block mb-2">End Date <span
                                        class="text-red-500">*</span></label>
                                <input name="offEndDate" id="offEndDate"
                                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                                    x-ref="endPicker" type="text" placeholder="Select end date" x-model="formData.offEndDate" />
                                <p x-show="showErrors && !isFieldValid('offEndDate')" class="text-red-500 text-sm mt-1"
                                    x-text="getErrorMessageById('offEndDate')"></p>
                            </div>
                        </div>
                    </div> 

                    <div class="my-4 border rounded shadow px-4 pt-4 pb-8">
                        <h3 class="text-xl font-bold p-2 mb-2 border-b">Candidate Information</h3>
                        <div class="flex space-x-4 mt-4">
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection