@extends('admin.layouts.app')

@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8" id="generalformwizard">
        @include('admin.layouts.partials.alerts')
        <div x-data="formData()" x-init="initSelect2" class="bg-white p-6 rounded-lg shadow-md">
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
                                    <input id="end_date" x-model="formFields.end_date" disable
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
                        <!-- Education Information Fields -->
                        <div x-show="selectedOption === 'education'" class="space-y-4">
                            <div>
                                <label for="degree" class="block text-sm font-medium text-gray-700 mb-2">Degree:</label>
                                <input type="text" id="degree" x-model="formFields.degree"
                                    class="w-full px-3 py-2 border rounded-md"
                                    :class="{'border-red-500': errors.degree}" />
                                <p x-show="errors.degree" x-text="errors.degree" class="text-red-500 text-xs mt-1"></p>
                            </div>
                            <div>
                                <label for="university"
                                    class="block text-sm font-medium text-gray-700 mb-2">University:</label>
                                <input type="text" id="university" x-model="formFields.university"
                                    class="w-full px-3 py-2 border rounded-md"
                                    :class="{'border-red-500': errors.university}" />
                                <p x-show="errors.university" x-text="errors.university"
                                    class="text-red-500 text-xs mt-1"></p>
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
function formData() {
    return {
        selectedOption: "",
        formFields: {
            timesheet: "",
            hiringmanager: "",
            worklocation: "",
            glaccount: "",
            vendoraccountmanager: "",
            contractorportal: '{{ old('contractorportal ', $contract->workOrder->consultant->candidate_id ?? '') }}',
            originalstartdate: '{{ old('originalstartdate ', formatDate($contract->workOrder->original_start_date) ?? '') }}',
            locationTax: '{{ old('locationTax ', $contract->workOrder->location_tax ?? '') }}',
            new_contract_start_date: '{{ old('new_contract_start_date ', formatDate($contract->workOrder->start_date) ?? '') }}',
            end_date: '{{ old('end_date ', formatDate($contract->workOrder->end_date) ?? '') }}',
            termination_date:"",
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
            if (!this.selectedOption) {
                this.errors.selectedOption = "Please select an option";
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