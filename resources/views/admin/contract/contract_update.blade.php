@extends('admin.layouts.app')

@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8" id="generalformwizard">
        @include('admin.layouts.partials.alerts')
        @php $rate = App\Services\RateshelpersService::returnContractEffectiveRate($contract->id);  @endphp
        <div x-data="formData({ id: @json($contract->id) })"  x-init="initSelect2" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Contract Update</h2>
            <form id="rawdata" @submit.prevent="submitForm">
                <div class="my-4">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h5>Update Assignment Reason<span style="float: right;"></span></h5>
    <div class="form-group m-t-20">
        <div class="card card-margin card-padding">
        <select name="update_contract_reason" id="update_contract_reason" 
        x-model="selectedOption" class="form-control select2" 
         required>
        <option value="">Select</option>
        @php
            $reasons = updateContractReason();
            $Error = '';
            $keysToUnset = [1, 2, 3, 5, 6];
            if ($contract->contractAdditionalBudgetRequest->isNotEmpty()) {
                $Error = "Additional budget request is pending.";
               
                foreach ($keysToUnset as $key){
                    unset($reasons[$key]);
                }
            }
            if($contract->latestApprovedExtensionRequest()){
                $Error = "Extension request is pending.";
                foreach ($keysToUnset as $key){
                    unset($reasons[$key]);
                }
            }
            if($contract->status == '6'){
                $Error = "Contract is Terminated.";
                foreach ($keysToUnset as $key){
                    unset($reasons[$key]);
                }
            }
        @endphp
        @foreach ($reasons as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
        @endforeach
            </select>
        <div id="assignmentupdateerror" style="color: red; display: {{ empty($Error) ? 'none' : 'block' }};">
            {{ $Error }}
        </div>
    </div>
            </div>

                </div>

                <div class="my-4">
                    <div class="p-[30px] rounded border" :style="{'border-color': 'var(--primary-color)'}">
                        <!-- no financial Fields -->
                        <div x-show="selectedOption === '4'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="timesheet" class="block mb-2">Timesheet Approving Manager
                                        <span class="text-red-500">*</span></label>
                                    @php $clients_hiring = \App\Models\Client::where('profile_status', 1)
                                    ->orderBy('first_name', 'ASC')
                                    ->get();
                                    $location = \App\Models\Location::byStatus();
                                    @endphp
                                    <select name="timesheet" class="w-full select2-single custom-style required" id="timesheet">
                                        <option value="">Select Timesheet Approving Manager</option>
                                        @foreach ($clients_hiring as $key => $value)
                                        <option value="{{ $value->id }}"
                                            <?= ($contract->workOrder->approval_manager == $value->id) ? "selected='selected'" : '' ?>>
                                            {{  $value->first_name.' '.$value->last_name; }}</option>
                                        @endforeach

                                    </select>
                                    <p x-show="errors.timesheet" x-text="errors.timesheet"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="hiringmanager" class="block mb-2">Hiring Manager
                                        <span class="text-red-500">*</span></label>
                                    <select name="hiringmanager" class="w-full select2-single custom-style required" id="hiringmanager">
                                        <option value="">Select location</option>
                                        @foreach ($clients_hiring as $key => $value)
                                        <option value="{{ $value->id }}"
                                            <?= ($contract->workOrder->hiring_manager_id == $value->id) ? "selected='selected'" : '' ?>>
                                            {{  $value->first_name.' '.$value->last_name; }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.hiringmanager" x-text="errors.hiringmanager"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="worklocation" class="block mb-2">
                                        Work Location
                                        <span class="text-red-500">*</span></label>
                                    <select name="worklocation" class="w-full select2-single custom-style required" id="worklocation">
                                        <option value="">Select location</option>
                                        @foreach ($location as $key => $value)
                                        <option value="{{ $value->id }}"
                                            <?= ($contract->workOrder->location_id == $value->id) ? "selected='selected'" : '' ?>>
                                            {{ locationName($value->id) }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.worklocation" x-text="errors.worklocation"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">

                                <div class="flex-1">
                                    <label for="vendoraccountmanager" class="block mb-2">
                                        Vendor Account Manager
                                        <span class="text-red-500">*</span></label>
                                    <select name="vendoraccountmanager" class="w-full select2-single custom-style required" id="vendoraccountmanager">
                                        <option value="">Select Vendor Account Manager</option>

                                        <!-- Vendor option -->
                                        <option value="{{ $contract->submission->vendor_id }}"
                                            {{ $contract->submission->emp_msp_account_mngr == $contract->submission->vendor_id ? 'selected' : '' }}>
                                            {{ $contract->submission->vendor->full_name }}
                                        </option>

                                        <!-- Team members options -->
                                        @foreach($contract->submission->vendor->teamMembers as $team)
                                        <option value="{{ $team->teammember_id }}"
                                            {{ $contract->submission->emp_msp_account_mngr == $team->teammember_id ? 'selected' : '' }}>
                                            {{ $team->teammember->full_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.vendoraccountmanager" x-text="errors.vendoraccountmanager"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="contractorportal" class="block mb-2">Contractor Portal ID
                                        <span class="text-red-500">*</span></label>
                                    <input name="contractorportal" type="text" id="contractorportal" x-model="formFields.contractorportal"
                                        @input="clearFieldError('contractorportal')"
                                        value="{{$contract->workOrder->consultant->candidate_id}}"
                                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                        placeholder="Enter Contractor Portal ID" />
                                    <p x-show="errors.contractorportal" x-text="errors.contractorportal"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="originalstartdate" class="block mb-2">Original Start Date:
                                        <span class="text-red-500">*</span></label>
                                    <input name="originalstartdate" id="originalstartdate" x-model="formFields.originalstartdate"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        type="text" placeholder="Select start date" />
                                    <p x-show="errors.originalstartdate" x-text="errors.originalstartdate"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>

                                <div class="flex-1">
                                    <label for="candidatesourcetype" class="block mb-2">
                                        Candidate Sourcing Type & Worker Type
                                        <span class="text-red-500">*</span></label>
                                    <select name="candidatesourcetype" class="w-full select2-single custom-style required" id="candidatesourcetype">
                                        <option value="">Select </option>
                                        @foreach (checksetting(18) as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $contract->workOrder->sourcing_type == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.candidatesourcetype" x-text="errors.candidatesourcetype"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="locationTax" class="block mb-2 capitalize">Location Tax </label>
                                    <div>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                            <input type="text" name="locationTax" 
                                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                                placeholder="00.00" id="locationTax" x-model="formFields.locationTax"
                                                @input="formatRate('locationTax', $event)"
                                                @blur="formatRate('locationTax', $event)" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label for="expensesallowed" class="block mb-2">Expense Allowed 
                                        <span class="text-red-500">*</span></label>
                                    <select name="expensesallowed" class="w-full select2-single custom-style required" id="expensesallowed">
                                        <option value="">Select location</option>
                                        <option value="yes"
                                            {{$contract->workOrder->expenses_allowed == "Yes" ? 'selected' : ''}}>Yes
                                        </option>
                                        <option value="no"
                                            {{$contract->workOrder->expenses_allowed == "No" ? 'selected' : ''}}>No
                                        </option>
                                    </select>
                                    <p x-show="errors.expensesallowed" x-text="errors.expensesallowed"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1"></div>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="businessjustification" class="block mb-2">Business Justification<span
                                            class="text-red-500">*</span></label>
                                    <textarea name="businessjustification" id="businessjustification" x-model="formFields.businessjustification"
                                        @input="clearFieldError('businessjustification')" class="w-full border rounded required"
                                        rows="5" :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Business Justification"></textarea>
                                    <p x-show="errors.businessjustification" x-text="errors.businessjustification"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>
                        <!-- Additional Budget Fields -->
                        <div x-show="selectedOption === '1'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for ="additional_budget_reason" class="block mb-2">
                                        Reason for Additional Budget
                                        <span class="text-red-500">*</span></label>
                                    <select name="additional_budget_reason" class="w-full select2-single required custom-style required" name="additional_budget_reason"
                                        id="additional_budget_reason">
                                        <option >Select </option>
                                        @foreach (checksetting(21) as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.additional_budget_reason" x-text="errors.additional_budget_reason"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="amount" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                                    <input type="text" name="amount" 
                                        class="w-full h-12 px-4 text-gray-500 required border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        placeholder="00.00" id="amount" name="amount" x-model="formFields.amount"
                                        @input="formatRate('amount', $event)" @blur="formatRate('amount', $event)" />
                                    <p x-show="errors.amount" x-text="errors.amount" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for= "additional_budget_notes" class="block mb-2">Notes<span class="text-red-500">*</span></label>
                                    <textarea id="additional_budget_notes" name="additional_budget_notes"
                                        x-model="formFields.additional_budget_notes"
                                        @input="clearFieldError('additional_budget_notes')"
                                        class="w-full border rounded required" rows="5"
                                        :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Notes"></textarea>
                                    <p x-show="errors.additional_budget_notes" x-text="errors.additional_budget_notes"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Extension rate Fields -->
                        <div x-show="selectedOption === '2' || selectedOption === '3'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                
                                <div class="flex-1">
                                    <label for="bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Bill Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        placeholder="00.00" id="bill_rate" name="bill_rate" x-model="formFields.bill_rate"
                                        @input="formatRate('bill_rate', $event)"  />
                                    <p x-show="errors.bill_rate" x-text="errors.bill_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="client_overtime_bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Client Over Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        placeholder="00.00" id="client_overtime_bill_rate" disabled name="client_overtime_bill_rate" x-model="formFields.client_overtime_bill_rate"
                                        @input="formatRate('client_overtime_bill_rate', $event)" @blur="formatRate('client_overtime_bill_rate', $event)" />
                                    <p x-show="errors.client_overtime_bill_rate" x-text="errors.client_overtime_bill_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="client_doubletime_bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Client Double Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="client_doubletime_bill_rate" disabled name="client_doubletime_bill_rate" x-model="formFields.client_doubletime_bill_rate"
                                        @input="formatRate('client_doubletime_bill_rate', $event)" @blur="formatRate('client_doubletime_bill_rate', $event)" />
                                    <p x-show="errors.client_doubletime_bill_rate" x-text="errors.client_doubletime_bill_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                
                                <div class="flex-1">
                                    <label for="pay_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Pay Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="pay_rate" name="pay_rate" x-model="formFields.pay_rate"
                                        @input="formatRate('pay_rate', $event)"   />
                                    <p x-show="errors.pay_rate" x-text="errors.pay_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="contractor_overtime_pay_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Contractor Over Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="contractor_overtime_pay_rate" disabled name="contractor_overtime_pay_rate" x-model="formFields.contractor_overtime_pay_rate"
                                        @input="formatRate('contractor_overtime_pay_rate', $event)" @blur="formatRate('contractor_overtime_pay_rate', $event)" />
                                    <p x-show="errors.contractor_overtime_pay_rate" x-text="errors.contractor_overtime_pay_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="contractor_double_time_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Contractor Double Time Rate<span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="contractor_double_time_rate" disabled name="contractor_double_time_rate" x-model="formFields.contractor_double_time_rate"
                                        @input="formatRate('contractor_double_time_rate', $event)" @blur="formatRate('contractor_double_time_rate', $event)" />
                                    <p x-show="errors.contractor_double_time_rate" x-text="errors.contractor_double_time_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="new_contract_start_date" class="block mb-2">Start Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="new_contract_start_date" x-model="formFields.new_contract_start_date" disabled
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        type="text" name="new_contract_start_date" placeholder="Select start date" />
                                    <p x-show="errors.new_contract_start_date" x-text="errors.new_contract_start_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="end_date" class="block mb-2">End Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="end_date" x-model="formFields.end_date" disabled
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="end_date" placeholder="Select start date" />
                                    <p x-show="errors.end_date" x-text="errors.end_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="markup" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Markup<span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="markup" name="markup" x-model="formFields.markup" disabled
                                        @input="formatRate('markup', $event)"  />
                                    <p x-show="errors.markup" x-text="errors.markup" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4"  x-show="selectedOption === '2'">
                                <div class="flex-1">
                                    <label class="block mb-2">
                                    Extension Reason
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" name="reason_of_extension"
                                        id="reason_of_extension">
                                        <option value="">Select </option>
                                        @foreach (checksetting(26) as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.reason_of_extension" x-text="errors.reason_of_extension"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="extension_date" class="block mb-2">Assignment New End Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="extension_date" name="extension_date" x-model="formFields.extension_date"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="extension_date" placeholder="Select start date" />
                                    <p x-show="errors.extension_date" x-text="errors.extension_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                        </div>
                        <div class="flex space-x-4 mt-4"  x-show="selectedOption === '3'">
                               
                                <div class="flex-1">
                                    <label for="effective_date" class="block mb-2">Effective Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="effective_date" name="effective_date" x-model="formFields.effective_date"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="effective_date" placeholder="Select start date" />
                                    <p x-show="errors.effective_date" x-text="errors.effective_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                        </div>

                            <div class="flex space-x-4 mt-4"  x-show="selectedOption === '2'">
                                <div class="flex-1">
                                    <label class="block mb-2">Notes<span class="text-red-500">*</span></label>
                                    <textarea id="additional_budget_notes" name="additional_budget_notes"
                                        x-model="formFields.additional_budget_notes"
                                        @input="clearFieldError('additional_budget_notes')"
                                        class="w-full border rounded" rows="5"
                                        :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Notes"></textarea>
                                    <p x-show="errors.additional_budget_notes" x-text="errors.additional_budget_notes"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>
                         <!-- Update Start Date Fields -->
                         <div x-show="selectedOption === '5'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="new_contract_start_date" class="block mb-2">Start Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="new_contract_start_date" x-model="formFields.new_contract_start_date"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="new_contract_start_date" placeholder="Select start date" />
                                    <p x-show="errors.new_contract_start_date" x-text="errors.new_contract_start_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="end_date" class="block mb-2">End Date:
                                        <span class="text-red-500">*</span></label>
                                    <input id="end_date" x-model="formFields.end_date" disabled
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="end_date" placeholder="Select start date" />
                                    <p x-show="errors.end_date" x-text="errors.end_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Termination Fields -->
                        <div x-show="selectedOption === '6'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="termination_reason" class="block mb-2">
                                        Reason for Termination
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style required" name="termination_reason"
                                        id="termination_reason">
                                        <option value="">Select </option>
                                        @foreach (checksetting(25) as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.termination_reason" x-text="errors.termination_reason"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="termination_date" class="block mb-2">Date of Termination/Assignment Closing:
                                        <span class="text-red-500">*</span></label>
                                    <input id="termination_date" name="termination_date" x-model="formFields.termination_date"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7 required"
                                        type="text" name="termination_date" placeholder="Select start date" />
                                    <p x-show="errors.termination_date" x-text="errors.termination_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label for="termination_feedback" class="block mb-2">Contractor Feedback<span class="text-red-500">*</span></label>
                                    <textarea id="termination_feedback" name="termination_feedback"
                                        x-model="formFields.termination_feedback"
                                        @input="clearFieldError('termination_feedback')"
                                        class="w-full border rounded required" rows="5"
                                        :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Notes"></textarea>
                                    <p x-show="errors.termination_feedback" x-text="errors.termination_feedback"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="termination_notes" class="block mb-2">Notes/Comments<span class="text-red-500">*</span></label>
                                    <textarea id="termination_notes" name="termination_notes"
                                        x-model="formFields.termination_notes"
                                        @input="clearFieldError('termination_notes')"
                                        class="w-full border rounded required" rows="5"
                                        :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Notes"></textarea>
                                    <p x-show="errors.termination_notes" x-text="errors.termination_notes"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
                        Submit
                    </button>
                </div>
            </form>

            <div x-show="submitted" class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                Form submitted successfully!
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function formData({ id }) {
    return {
        currentId: '{{$contract->id}}',
        selectedOption: "",
        formFields: {
            timesheet: "",
            hiringmanager: "",
            worklocation: "",
            glaccount: "",
            bill_rate:'{{ old('bill_rate ', $rate["bill_rate"] ?? '') }}',
            client_overtime_bill_rate:'{{ old('client_overtime_bill_rate ', $rate["client_overtime_bill_rate"] ?? '') }}',
            client_doubletime_bill_rate:'{{ old('client_doubletime_bill_rate ', $rate["client_doubletime_bill_rate"] ?? '') }}',
            pay_rate:'{{ old('pay_rate ', $rate["pay_rate"] ?? '') }}',
            contractor_overtime_pay_rate:'{{ old('contractor_overtime_pay_rate ', $rate["contractor_overtime_pay_rate"] ?? '') }}',
            contractor_double_time_rate:'{{ old('contractor_double_time_rate ', $rate["contractor_double_time_rate"] ?? '') }}',
            markup: '{{ old('markup ', $contract->workOrder->markup ?? '') }}',
            submissionid:'{{ old('submissionid ', $contract->workOrder->submission_id ?? '') }}',
            vendoraccountmanager: "",
            contractorportal: '{{ old('contractorportal ', $contract->workOrder->consultant->candidate_id ?? '') }}',
            originalstartdate: '{{ old('originalstartdate ', formatDate($contract->workOrder->original_start_date) ?? '') }}',
            locationTax: '{{ old('locationTax ', $contract->workOrder->location_tax ?? '') }}',
            new_contract_start_date: '{{ old('new_contract_start_date ', formatDate($contract->workOrder->start_date) ?? '') }}',
            end_date: '{{ old('end_date ', formatDate($contract->workOrder->end_date) ?? '') }}',
            termination_date:"",
            effective_date:'{{ old('effective_date ', formatDate($contract->workOrder->effective_date) ?? '') }}',
            extension_date:"",
            labortype: "",
            candidatesourcetype: "",
            businessjustification: "",
        },
        errors: {},
        submitted: false,
        billRate: "",
        billRateError: "",

        updateFields() {
            console.log(
                "updateFields called. Selected option:",
                this.selectedOption
            );
            this.errors = {};
        },

        initSelect2() {
            $(document).ready(() => {
                $(".select2-single").each((i, elem) => {
                    $(elem)
                        .select2({
                            width: "100%",
                        })
                        .on("select2:select", (e) => {
                            this.formFields[e.target.id] = e.target.value;
                            this.clearFieldError(e.target.id);
                        });
                });

                $("#select-option").on("select2:select", (e) => {
                    this.selectedOption = e.params.data.id;
                    console.log(
                        "Select2 change event. New value:",
                        this.selectedOption
                    );
                    this.$nextTick(() => {
                        this.updateFields();
                    });
                });
            });
        },

        initDatePickers() {
           let minDate = "{{formatDate($contract->workOrder->start_date)}}"; // Set the minimum date
           let maxDate = "{{formatDate($contract->workOrder->end_date)}}"; // Set the maximum date
            this.$nextTick(() => {
                flatpickr("#originalstartdate", {
                    dateFormat: "m/d/Y",
                    minDate: minDate, // Set the minimum date
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.originalstartdate = dateStr;
                        this.clearFieldError("originalstartdate");
                    },
                });
                // start date
                flatpickr("#new_contract_start_date", {
                    dateFormat: "m/d/Y",
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.new_contract_start_date = dateStr;
                        this.clearFieldError("new_contract_start_date");
                    },
                });
                // end date
                flatpickr("#end_date", {
                    dateFormat: "m/d/Y",
                    minDate: minDate, // Set the minimum date
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.end_date = dateStr;
                        this.clearFieldError("end_date");
                    },
                });
                flatpickr("#termination_date", {
                    dateFormat: "m/d/Y",
                    minDate: minDate, // Set the minimum date
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.termination_date = dateStr;
                        this.clearFieldError("termination_date");
                    },
                });
                flatpickr("#extension_date", {
                    dateFormat: "m/d/Y",
                    minDate: minDate, // Set the minimum date
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.extension_date = dateStr;
                        this.clearFieldError("extension_date");
                    },
                });
                flatpickr("#effective_date", {
                    dateFormat: "m/d/Y",
                    minDate: minDate, // Set the minimum date
                    maxDate: maxDate, // Set the maximum date
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.effective_date = dateStr;
                        this.clearFieldError("effective_date");
                    },
                });
            });
        },

        formatRate(field, event) {
            let value = event.target.value.replace(/[^0-9.]/g, "");
            if (value.split(".").length > 2) {
                value = value.replace(/\.+$/, "");
            }
            const parts = value.split(".");
            if (parts[0].length > 3) {
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            if (parts[1] && parts[1].length > 2) {
                parts[1] = parts[1].substring(0, 2);
            }
            this[field] = parts.join(".");
            this.clearFieldError(field);
            console.log(field);
            
            if(field == "bill_rate" || field == "pay_rate") {
            this.calculateRates(field);
            }
        },
            calculateRates(event){
                      var bill_rate = document.getElementById("bill_rate").value;
                      var payRateElement = document.getElementById("pay_rate");
                      var pay_rate;

                      if (payRateElement) {
                          pay_rate = payRateElement.value;
                      } else {
                          pay_rate = 0;
                      }

                      pay_rate = parseFloat(pay_rate); // Ensure it's a number


                      // console.log(markup);

                      // $('#pay_rate_error_message').html('');

                      if (pay_rate == '') {
                          pay_rate = 0;
                      }
                      let url = `/calculate-rate`;
                      let data = new FormData();

                      // Append form data
                      data.append('pay_rate', pay_rate);
                      data.append('bill_rate', bill_rate);

                      data.append('markup', this.formFields.markup);
                      data.append('submission_id', this.formFields.submissionid);

                      // Determine which field was changed

                      data.append('type', event);

                        // console.log(this.formData); return false;

                      // AJAX call to update fields based on pay rate or bill rate change
                      const updates = {
                        //   '#billRate': { type: 'value', field: 'billRate' },
                        //   '#payRate': { type: 'value', field: 'payRate' },
                          '#client_overtime_bill_rate': { type: 'value', field: 'overTime' },
                          '#client_doubletime_bill_rate': { type: 'value', field: 'doubleRate' },
                          
                          '#contractor_overtime_pay_rate': { type: 'value', field: 'overTimeCandidate' },
                          '#contractor_double_time_rate': { type: 'value', field: 'doubleTimeCandidate' },
                          '#markup': { type: 'value', field: 'markup_contract' },
                        // Add more mappings as needed
                      };
                      ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
                      const intervalId = setInterval(() => {
                        //   const billRate = $('#bill_rate').val();
                        //   const payRate = $('#pay_rate').val();
                          const client_overtime_bill_rate = $('#client_overtime_bill_rate').val();
                          const client_doubletime_bill_rate= $('#client_doubletime_bill_rate').val();
                          const contractor_overtime_pay_rate = $('#contractor_overtime_pay_rate').val();
                          const contractor_double_time_rate = $('#contractor_double_time_rate').val();
                          
                          const markup = $('#markup').val();

                          // Only set formData when all the required fields have been populated
                            //   this.formFields.bill_rate = billRate;
                            //   this.formFields.pay_rate = payRate;
                              this.formFields.client_overtime_bill_rate = client_overtime_bill_rate;
                              this.formFields.client_doubletime_bill_rate = client_doubletime_bill_rate;
                              this.formFields.contractor_overtime_pay_rate = contractor_overtime_pay_rate;
                              this.formFields.contractor_double_time_rate =contractor_double_time_rate;
                              this.formFields.markup =markup;
                              // Enable the fields or do further processing

                              // Clear the interval
                              clearInterval(intervalId);
                      }, 100);


            },

        clearFieldError(fieldName) {
            if (this.errors[fieldName]) {
                delete this.errors[fieldName];
            }
            if (fieldName === "billRate") {
                this.billRateError = "";
            }
        },

            validateForm() {
                this.errors = {};
                
                // Validate if an option is selected
                if (!this.selectedOption) {
                    this.errors.selectedOption = "Please select an option";
                }

                const form = document.querySelector('#rawdata');
                const formFields = form.querySelectorAll('[name].required'); // Select all fields with a name attribute

                formFields.forEach((field) => {
                    // Check if the field should be shown based on the selectedOption
                    const parentDiv = field.closest('[x-show]'); // Find the nearest ancestor with `x-show`

                    if ((parentDiv && parentDiv.style.display !== 'none') || !parentDiv) {
                        if (field.tagName.toLowerCase() === 'select') {
                            // Special handling for select fields
                            if (!field.value || field.value === "Select") {
                                const label = form.querySelector(`label[for="${field.id}"]`) || field.closest('label');
                                const labelText = label ? label.childNodes[0].textContent.trim() : field.name;
                                this.errors[field.name] = `${labelText} is required`;
                            }
                        } else if (!field.value.trim()) {
                            // Handle input fields
                            const label = form.querySelector(`label[for="${field.id}"]`) || field.closest('label');
                            const labelText = label ? label.childNodes[0].textContent.trim() : field.name;
                            this.errors[field.name] = `${labelText} is required`;
                        }
                    }
                });

                return Object.keys(this.errors).length === 0;
            },


        submitForm() {
            if (this.validateForm()) {
            const form = document.querySelector('#rawdata');
            const formFields = form.querySelectorAll('[name]');
            let formRecord = new FormData();
                formRecord.append('selectedOption', this.selectedOption);
                formRecord.append('contractId', this.currentId);
                formRecord.append('_method', 'PUT');
           
                for (const field of formFields) {
                    const parentDiv = field.closest('[x-show]'); // Find the nearest ancestor with `x-show`

                    if ((parentDiv && parentDiv.style.display !== 'none') || !parentDiv) {
                    formRecord.append(field.name, field.value);
                    }
                }
                // console.log(formRecord); return false;
                
/*for (let [key, value] of formRecord.entries()) {
            console.log(`${key}: ${value}`);
        }
*/            const url = `/admin/contracts/${this.currentId}`;
                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formRecord);
                
            } else {
                console.log("Form validation failed");
            }
        },

        init() {
            this.initSelect2();
            this.initDatePickers();

            // Sync Select2 values with formFields
            $(".select2-single").each((i, elem) => {
                this.formFields[elem.id] = $(elem).val() || "";
            });

            // Watch for changes in formFields
            this.$watch(
                "formFields",
                (value) => {
                    Object.keys(value).forEach((key) => {
                        if (value[key].trim()) {
                            this.clearFieldError(key);
                        }
                    });
                }, {
                    deep: true
                }
            );
        },
    };
}

document.addEventListener("alpine:init", () => {
    Alpine.data("formData", formData);
});
</script>