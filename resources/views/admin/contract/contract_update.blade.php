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
            <form @submit.prevent="submitForm">
                <div class="my-4">
                    <div class="p-[30px] rounded border" :style="{'border-color': 'var(--primary-color)'}">
                        <label for="select-option" class="block mb-2">Select Update Assignment Reason:
                            <span class="text-red-500">*</span></label>

                        <select id="select-option" x-model="selectedOption" @change="updateFields()"
                            class="w-full select2-single custom-style"
                            :class="{'border-red-500': errors.selectedOption}">
                            <option value="">Select...</option>
                            @foreach (updateContractReason() as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                            @endforeach

                        </select>
                        <p x-show="errors.selectedOption" x-text="errors.selectedOption"
                            class="text-red-500 text-xs mt-1"></p>
                    </div>
                </div>

                <div class="my-4">
                    <div class="p-[30px] rounded border" :style="{'border-color': 'var(--primary-color)'}">
                        <!-- no financial Fields -->
                        <div x-show="selectedOption === '4'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label class="block mb-2">Timesheet Approving Manager
                                        <span class="text-red-500">*</span></label>
                                    @php $clients_hiring = \App\Models\Client::where('profile_status', 1)
                                    ->orderBy('first_name', 'ASC')
                                    ->get();
                                    $location = \App\Models\Location::byStatus();
                                    @endphp
                                    <select class="w-full select2-single custom-style" id="timesheet">
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
                                    <label class="block mb-2">Hiring Manager
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" id="hiringmanager">
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
                                    <label class="block mb-2">
                                        Work Location
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" id="worklocation">
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
                                    <label class="block mb-2">
                                        Vendor Account Manager
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" id="vendoraccountmanager">
                                        <option value="">Select location</option>

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
                                    <label class="block mb-2">Contractor Portal ID
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" id="contractorportal" x-model="formFields.contractorportal"
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
                                    <input id="originalstartdate" x-model="formFields.originalstartdate"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" placeholder="Select start date" />
                                    <p x-show="errors.originalstartdate" x-text="errors.originalstartdate"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>

                                <div class="flex-1">
                                    <label class="block mb-2">
                                        Candidate Sourcing Type & Worker Type
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" id="candidatesourcetype">
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
                                    <label for="billRate" class="block mb-2 capitalize">Location Tax </label>
                                    <div>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                            <input type="text"
                                                class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                                placeholder="00.00" id="locationTax" x-model="formFields.locationTax"
                                                @input="formatRate('locationTax', $event)"
                                                @blur="formatRate('locationTax', $event)" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Expense Allowed {{$contract->workOrder->expenses_allowed}}
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" id="expensesallowed">
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
                                    <label class="block mb-2">Business Justification<span
                                            class="text-red-500">*</span></label>
                                    <textarea id="businessjustification" x-model="formFields.businessjustification"
                                        @input="clearFieldError('businessjustification')" class="w-full border rounded"
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
                                    <label class="block mb-2">
                                        Reason for Additional Budget
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" name="additional_budget_reason"
                                        id="AdditionalBuget">
                                        <option value="">Select </option>
                                        @foreach (checksetting(21) as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.AdditionalBuget" x-text="errors.AdditionalBuget"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="amount" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="amount" name="amount" x-model="formFields.amount"
                                        @input="formatRate('amount', $event)" @blur="formatRate('amount', $event)" />
                                    <p x-show="errors.amount" x-text="errors.amount" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-4">
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

                        <!-- Extension rate Fields -->
                        <div x-show="selectedOption === '2'" class="space-y-4">
                            <div class="flex space-x-4 mt-4">
                                
                                <div class="flex-1">
                                    <label for="bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Bill Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="bill_rate" name="bill_rate" x-model="formFields.bill_rate"
                                        @input="formatRate('bill_rate', $event)" onchange="calculateRates('bill_rate')" @blur="formatRate('bill_rate', $event)" />
                                    <p x-show="errors.bill_rate" x-text="errors.bill_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="client_overtime_bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Client Over Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="client_overtime_bill_rate" name="client_overtime_bill_rate" x-model="formFields.client_overtime_bill_rate"
                                        @input="formatRate('client_overtime_bill_rate', $event)" @blur="formatRate('client_overtime_bill_rate', $event)" />
                                    <p x-show="errors.client_overtime_bill_rate" x-text="errors.client_overtime_bill_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="client_doubletime_bill_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Client Double Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="client_doubletime_bill_rate" name="client_doubletime_bill_rate" x-model="formFields.client_doubletime_bill_rate"
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
                                        @input="formatRate('pay_rate', $event)" onchange="calculateRates('pay_rate')" @blur="formatRate('pay_rate', $event)" />
                                    <p x-show="errors.pay_rate" x-text="errors.pay_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="contractor_overtime_pay_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Contractor Over Time Rate <span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="contractor_overtime_pay_rate" name="contractor_overtime_pay_rate" x-model="formFields.contractor_overtime_pay_rate"
                                        @input="formatRate('contractor_overtime_pay_rate', $event)" @blur="formatRate('contractor_overtime_pay_rate', $event)" />
                                    <p x-show="errors.contractor_overtime_pay_rate" x-text="errors.contractor_overtime_pay_rate" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <label for="contractor_double_time_rate" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Contractor Double Time Rate<span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="contractor_double_time_rate" name="contractor_double_time_rate" x-model="formFields.contractor_double_time_rate"
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
                                <div class="flex-1">
                                    <label for="markup" 
                                        class="block text-sm font-medium text-gray-700 mb-2">Markup<span class="text-red-500">*</span></label>
                                    <input type="text"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        placeholder="00.00" id="markup" name="markup" x-model="formFields.markup" disabled
                                        @input="formatRate('markup', $event)" @blur="formatRate('markup', $event)" />
                                    <p x-show="errors.markup" x-text="errors.markup" class="text-red-500 text-xs mt-1">
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-4">
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

                            <div class="flex space-x-4 mt-4">
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
                                    <label class="block mb-2">
                                        Reason for Termination
                                        <span class="text-red-500">*</span></label>
                                    <select class="w-full select2-single custom-style" name="additional_budget_reason"
                                        id="AdditionalBuget">
                                        <option value="">Select </option>
                                        @foreach (checksetting(25) as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p x-show="errors.AdditionalBuget" x-text="errors.AdditionalBuget"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label for="termination_date" class="block mb-2">Date of Termination/Assignment Closing:
                                        <span class="text-red-500">*</span></label>
                                    <input id="termination_date" name="termination_date" x-model="formFields.termination_date"
                                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                                        type="text" name="termination_date" placeholder="Select start date" />
                                    <p x-show="errors.termination_date" x-text="errors.termination_date"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-4">
                                <div class="flex-1">
                                    <label class="block mb-2">Contractor Feedback<span class="text-red-500">*</span></label>
                                    <textarea id="termination_can_feedback" name="termination_can_feedback"
                                        x-model="formFields.termination_can_feedback"
                                        @input="clearFieldError('termination_can_feedback')"
                                        class="w-full border rounded" rows="5"
                                        :style="{'border-color': 'var(--primary-color)'}"
                                        placeholder="Enter Notes"></textarea>
                                    <p x-show="errors.termination_can_feedback" x-text="errors.termination_can_feedback"
                                        class="text-red-500 text-xs mt-1"></p>
                                </div>
                                <div class="flex-1">
                                    <label class="block mb-2">Notes/Comments<span class="text-red-500">*</span></label>
                                    <textarea id="termination_notes" name="termination_notes"
                                        x-model="formFields.termination_notes"
                                        @input="clearFieldError('termination_notes')"
                                        class="w-full border rounded" rows="5"
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
        currentRowId: id,
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
            vendoraccountmanager: "",
            contractorportal: '{{ old('contractorportal ', $contract->workOrder->consultant->candidate_id ?? '') }}',
            originalstartdate: '{{ old('originalstartdate ', formatDate($contract->workOrder->original_start_date) ?? '') }}',
            locationTax: '{{ old('locationTax ', $contract->workOrder->location_tax ?? '') }}',
            new_contract_start_date: '{{ old('new_contract_start_date ', formatDate($contract->workOrder->start_date) ?? '') }}',
            end_date: '{{ old('end_date ', formatDate($contract->workOrder->end_date) ?? '') }}',
            termination_date:"",
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
            this.$nextTick(() => {
                flatpickr("#originalstartdate", {
                    dateFormat: "m/d/Y",
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.originalstartdate = dateStr;
                        this.clearFieldError("originalstartdate");
                    },
                });
                // start date
                flatpickr("#new_contract_start_date", {
                    dateFormat: "m/d/Y",
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.new_contract_start_date = dateStr;
                        this.clearFieldError("new_contract_start_date");
                    },
                });
                // end date
                flatpickr("#end_date", {
                    dateFormat: "m/d/Y",
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.end_date = dateStr;
                        this.clearFieldError("end_date");
                    },
                });
                flatpickr("#termination_date", {
                    dateFormat: "m/d/Y",
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.termination_date = dateStr;
                        this.clearFieldError("termination_date");
                    },
                });
                flatpickr("#extension_date", {
                    dateFormat: "m/d/Y",
                    onChange: (selectedDates, dateStr) => {
                        this.formFields.extension_date = dateStr;
                        this.clearFieldError("extension_date");
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
            this.calculateRates(field);
        },
        calculateRates(event){
                      var bill_rate = document.getElementById("bill_rate").value;
                      var payRateElement = document.getElementById("payRate");
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

                      data.append('markup', this.formData.markup);
                      data.append('submission_id', this.formData.submissionid);

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
                          const billRate = $('#billRate').val();
                          const payRate = $('#payRate').val();
                          const client_overtime_bill_rate = $('#client_overtime_bill_rate').val();
                          const client_doubletime_bill_rate= $('#client_doubletime_bill_rate').val();
                          const contractor_overtime_pay_rate = $('#contractor_overtime_pay_rate').val();
                          const contractor_double_time_rate = $('#contractor_double_time_rate').val();
                          over_time
                          const markup = $('#markup').val();

                          // Only set formData when all the required fields have been populated
                              this.formData.billRate = billRate;
                              this.formData.payRate = payRate;
                              this.formData.client_overtime_bill_rate = client_overtime_bill_rate;
                              this.formData.client_doubletime_bill_rate = client_doubletime_bill_rate;
                              this.formData.contractor_overtime_pay_rate = contractor_overtime_pay_rate;
                              this.formData.contractor_double_time_rate =contractor_double_time_rate;
                              this.formData.markup =markup;
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
//            console.log("Selected option:", this.selectedOption);
            this.errors = {};
            if (!this.selectedOption) {
                this.errors.selectedOption = "Please select an option";
            }
            if (this.selectedOption == '4') 
            {
                const timesheet = document.getElementById('timesheet').value;
                if (!timesheet) {
                    this.errors.timesheet = "Timesheet Approving Manager is required";
                }
                const hiringManager = document.getElementById('hiringmanager').value;
                if (!hiringManager) {
                    this.errors.hiringmanager = "Hiring Manager is required";
                }

                // Validate Work Location
                const workLocation = document.getElementById('worklocation').value;
                if (!workLocation) {
                    this.errors.worklocation = "Work Location is required";
                }
                const vendorAccountManager = document.getElementById('vendoraccountmanager').value;
                if (!vendorAccountManager) {
                    this.errors.vendoraccountmanager = "Vendor Account Manager is required";
                }
                if (!this.formFields.contractorportal.trim()) {
                    this.errors.contractorportal = "Contractor Portal ID is required";
                }

                // Validate Original Start Date
                if (!this.formFields.originalstartdate.trim()) {
                    this.errors.originalstartdate = "Original Start Date is required";
                }
                const candidateSourceType = document.getElementById('candidatesourcetype').value;
                if (!candidateSourceType) {
                    this.errors.candidatesourcetype = "Candidate Sourcing Type is required";
                }
                if (!this.formFields.locationTax.trim()) {
                    this.errors.locationTax = "Location Tax is required";
                }
                const expensesAllowed = document.getElementById('expensesallowed').value;
                if (!expensesAllowed) {
                    this.errors.expensesallowed = "Expense Allowed is required";
                }
                 if (!this.formFields.businessjustification.trim()) {
                    this.errors.businessjustification = "Business Justification is required";
                }
                // Log all validation results
                if (Object.keys(this.errors).length > 0) {
                    console.log("Validation errors for step 4:", this.errors);
                    return this.errors; // Return errors for step 4
                } else {
                    console.log("All validation for step 4 are done");
                    return {}; // Return an empty object if no errors
                }
            }
            Object.keys(this.formFields).forEach((field) => {
                if (!this.formFields[field].trim()) {
                    this.errors[field] = `${
                  field.charAt(0).toUpperCase() +
                  field
                    .slice(1)
                    .replace(/([A-Z])/g, " $1")
                    .trim()
                } is required`;
                }
            });

            if (!this.billRate) {
                this.billRateError = "Bill Rate is required";
            } else if (isNaN(parseFloat(this.billRate.replace(/,/g, "")))) {
                this.billRateError = "Bill Rate must be a valid number";
            } else {
                this.billRateError = "";
            }

            console.log("Validation errors:", this.errors);
            console.log("Current form fields:", this.formFields);

            return Object.keys(this.errors).length === 0 && !this.billRateError;
        },

        submitForm() {
            if (this.validateForm()) {
                console.log(
                    "Form submitted:",
                    this.selectedOption,
                    this.formFields,
                    this.billRate
                );
                this.submitted = true;
//                let formData = new FormData();
                if (this.selectedOption === '4') 
            {
                console.log("here i am ");
                let formData = new FormData();
                formData.append('selectedOption', this.selectedOption);
                formData.append('timesheet', document.getElementById('timesheet').value);
                formData.append('hiringManager', document.getElementById('hiringmanager').value);
                formData.append('workLocation', document.getElementById('worklocation').value);
                formData.append('vendorAccountManager', document.getElementById('vendoraccountmanager').value);
                formData.append('contractorPortal', this.formFields.contractorportal);
                formData.append('contractId', this.currentRowId);
                formData.append('originalStartDate', this.formFields.originalstartdate);
                formData.append('candidateSourceType', document.getElementById('candidatesourcetype').value);
                formData.append('locationTax', this.formFields.locationTax);
                formData.append('expensesAllowed', document.getElementById('expensesallowed').value);
                formData.append('businessJustification', this.formFields.businessjustification);
                formData.append('_method', 'PUT');
                const url = `/admin/contracts/${this.currentRowId}`;
//                console.log("FormData contents:", [...formData.entries()]);
                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);
                this.openModal = false;
            }


                setTimeout(() => {
                    this.selectedOption = "";
                    Object.keys(this.formFields).forEach(
                        (key) => (this.formFields[key] = "")
                    );
                    this.billRate = "";
                    this.submitted = false;
                    $(".select2-single").val(null).trigger("change");
                    flatpickr("#originalstartdate").clear();
                }, 3000);
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