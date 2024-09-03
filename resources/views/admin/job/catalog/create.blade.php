@extends('admin.layouts.app')

@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')

<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8">
        <div x-data='jobCatalog({!! json_encode($job) !!})' x-init="$nextTick(() => init())"
            @rate-card-updated.window="updateJobCatalogRateCards($event.detail)">
            @include('admin.layouts.partials.alerts')
            <!-- Success Notification -->
            <!-- <div
              x-show="showSuccessMessage"
              class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded"
            >
              Job catalog is added successfully.
            </div> -->
            <form @submit.prevent="submitForm" id="generalformwizard" method="POST" >
                @if(isset($job->job_title))
                @method('PUT')
                @endif
                <!-- First row -->
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label for="jobTitle" class="block mb-2">Job Title <span class="text-red-500">*</span></label>
                        <input type="text" name="job_title" x-model="formData.jobTitle"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            placeholder="Add job title" id="jobTitle" />
                        <p x-show="showErrors && !isFieldValid('jobTitle')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobTitle')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Job Labor Category
                            <span class="text-red-500">*</span></label>
                        <select x-ref="laborCategory " name="cat_id" x-model="formData.laborCategory"
                            class="w-full select2-single custom-style" data-field="laborCategory" id="laborCategory"
                            x-init="$nextTick(() => {
            let select2Element = $refs.laborCategory;
            $(select2Element).select2().on('select2:select', function(event) {
                formData.laborCategory = event.target.value;
            });
        })">
                            <option value="">Select labor category</option>
                            @foreach (checksetting(5) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('laborCategory')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('laborCategory')"></p>
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Profile Worker Type
                            <span class="text-red-500">*</span></label>
                        <select x-ref="profileWorkerType" x-model="formData.profileWorkerType"
                            name="profile_worker_type_id" class="w-full select2-single custom-style"
                            data-field="profileWorkerType" id="profileWorkerType"
                            x-init="$nextTick(() => {
            let select2Element = $refs.profileWorkerType;
            $(select2Element).select2().on('select2:select', function(event) {
                formData.profileWorkerType = event.target.value;
            });
        })">
                            <option value="">Select profile worker type</option>
                            @foreach (checksetting(4) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('profileWorkerType')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('profileWorkerType')"></p>
                    </div>
                </div>
                <!-- Second row -->
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block mb-2">Worker Type <span class="text-red-500">*</span></label>
                        <select x-ref="workerType " name="worker_type_id" x-model="formData.workerType"
                            class="w-full select2-single custom-style" data-field="workerType" id="workerType"
                            x-init="$nextTick(() => {
            let select2Element = $refs.workerType;
            $(select2Element).select2().on('select2:select', function(event) {
                formData.workerType = event.target.value;
            });
        })">
                            <option value="">Select worker type</option>
                            @foreach (checksetting(3) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('workerType')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('workerType')"></p>
                    </div>
                    <div class="flex-1">
                        <label for="jobCode" class="block mb-2">Job Code <span class="text-red-500">*</span></label>
                        <input type="text" name="job_code" x-model="formData.jobCode"
                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                            value="106207" placeholder="106207" id="jobCode" />
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2">Job Family <span class="text-red-500">*</span></label>
                        <select x-ref="jobFamily" name="job_family_id" x-model="formData.jobFamily"
                            class="w-full select2-single custom-style" data-field="jobFamily" id="jobFamily"
                            x-init="$nextTick(() => {
            let select2Element = $refs.workerType;
            $(select2Element).select2().on('select2:select', function(event) {
                formData.workerType = event.target.value;
            });
        })">
                            <option value="">Select job family</option>
                            @foreach (getActiveRecordsByType('job-family') as $record)
                            <option value="{{ $record->id }}">{{ $record->name }}</option>
                            @endforeach
                        </select>
                        <p x-show="showErrors && !isFieldValid('jobFamily')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobFamily')"></p>
                    </div>
                </div>
                <!-- Third row -->
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" x-ref="jobCatalogStatus " x-model="formData.jobCatalogStatus"
                            class="w-full select2-single custom-style" data-field="jobCatalogStatus"
                            id="jobCatalogStatus"                 x-init="$nextTick(() => {
                            let select2Element = $refs.jobCatalogStatus;
                            $(select2Element).select2().on('select2:select', function(event) {
                                formData.jobCatalogStatus = event.target.value;
                            });
                        })">
                            <option value="">Select status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <p x-show="showErrors && !isFieldValid('jobCatalogStatus')" class="text-red-500 text-sm mt-1"
                            x-text="getErrorMessageById('jobCatalogStatus')"></p>
                    </div>
                    <div class="flex-1"></div>
                    <div class="flex-1"></div>
                </div>
                <!-- Fourth row - Text Editor -->
                <div class="mt-4">
                    <label class="block mb-2">Description <span class="text-red-500">*</span></label>
                    <div id="jobDescription" style="height: 300px"></div>
                    <p x-show="showErrors && !isFieldValid('jobDescription')" class="text-red-500 text-sm mt-1"
                        x-text="getErrorMessageById('jobDescription')"></p>
                </div>
                <!-- Job Catalog Rate Card -->
                @php
                $jobLevels = checksetting(1);
                $currencies = checksetting(2);
                @endphp

                <div x-data="jobCatalogRateCard()" x-init="init" @reset-rate-card.window="resetEntries">
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <div class="flex flex-wrap -mx-3 mb-4">
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                                <label class="block mb-2">
                                    Job Level <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                <select
                          x-model="newEntry.jobLevel"
                          class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                          id="job-level"
                        >
                                        <option value="">Select Job Level</option>
                                        @foreach (checksetting(1) as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                                <label class="block mb-2">
                                    Minimum Bill Rate <span class="text-red-500">*</span>
                                </label>
                                <input x-model="newEntry.minBillRate" name="min_bill_rate[]"
                                    @input="formatCurrency('minBillRate')"
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="min-bill-rate" type="text" placeholder="0.00" />
                                <p class="text-red-500 text-xs italic mt-1" x-show="errors.minBillRate"
                                    x-text="errors.minBillRate"></p>
                            </div>
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                                <label class="block mb-2">
                                    Maximum Bill Rate <span class="text-red-500">*</span>
                                </label>
                                <input x-model="newEntry.maxBillRate" name="temp_min_billrate[]"
                                    @input="formatCurrency('maxBillRate')"
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="max-bill-rate" type="text" placeholder="0.00" />
                                <p class="text-red-500 text-xs italic mt-1" x-show="errors.maxBillRate"
                                    x-text="errors.maxBillRate"></p>
                            </div>
                            <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0" >
                                <label class="block mb-2"> Currency </label>
                                <div class="relative">
                                <select
                                                x-model="newEntry.currency"
                                                class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                                id="currency"
                                                >
                                        <option value="">Select Currency</option>
                                        @foreach (checksetting(2) as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <p x-show="showErrors && !isValid()" class="text-red-500 text-sm mt-1">
                                At least one Job Catalog Rate Card entry is required
                            </p>
                        </div>
                        <div class="flex justify-end">
                            <button @click.prevent="addEntry(); $dispatch('rate-card-updated', entries)"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add
                            </button>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4">Job Catalog-Rate Card</h2>
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th
                                        class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200 text-left">
                                        Job Level
                                    </th>
                                    <th
                                        class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200 text-left">
                                        Minimum Bill Rate
                                    </th>
                                    <th
                                        class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200 text-left">
                                        Maximum Bill Rate
                                    </th>
                                    <th
                                        class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200 text-left">
                                        Currency
                                    </th>
                                    <th
                                        class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200 text-left">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(entry, index) in entries" :key="index">
                                    <tr>
                                        <td class="py-3 px-4 border-b border-gray-200"
                                            x-text="getJobLevelText(entry.jobLevel)"></td>
                                        <td class="py-3 px-4 border-b border-gray-200"
                                            x-text="formatCurrencyDisplay(entry.minBillRate, entry.currency)"></td>
                                        <td class="py-3 px-4 border-b border-gray-200"
                                            x-text="formatCurrencyDisplay(entry.maxBillRate, entry.currency)"></td>
                                        <td class="py-3 px-4 border-b border-gray-200">
                                            <i :class="getCurrencyIcon(entry.currency)" class="mr-1"></i>
                                            <span x-text="getCurrencyText(entry.currency)"></span>
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-200">
                                            <button @click="deleteEntry(index)"
                                                class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Navigation buttons -->
                <div class="flex justify-between mt-6">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function jobCatalogRateCard() {
    return {
        entries:  @json($ratecards),
        newEntry: {
            jobLevel: "",
            minBillRate: "",
            maxBillRate: "",
            currency: "",
        },
        errors: {
            minBillRate: "",
            maxBillRate: "",
        },
        jobLevels: @json($jobLevels), // PHP array converted to JavaScript object
        currencies: @json($currencies), // PHP array converted to JavaScript object
        showErrors: false,
        // Get text based on key
        getJobLevelText(key) {
            return this.jobLevels[key] || key;
        },
        getCurrencyText(key) {
            return this.currencies[key] || key;
        },
        init() {
            this.$watch("entries", (value) => {
                console.log("pakistan" + value);

                this.$dispatch("rate-card-updated", value);
            });
        },

        addEntry() {
            console.log("addEntry function called");
            console.log("Current newEntry:", this.newEntry);
            // return false;
            this.errors.minBillRate = "";
            this.errors.maxBillRate = "";

            if (!this.newEntry.minBillRate) {
                this.errors.minBillRate = "Minimum Bill Rate is required";
            }
            if (!this.newEntry.maxBillRate) {
                this.errors.maxBillRate = "Maximum Bill Rate is required";
            }

            console.log("Errors after validation:", this.errors);

            if (this.newEntry.minBillRate && this.newEntry.maxBillRate) {
                const min = parseFloat(
                    this.newEntry.minBillRate.replace(/,/g, "")
                );
                const max = parseFloat(
                    this.newEntry.maxBillRate.replace(/,/g, "")
                );

                if (min > max) {
                    this.errors.maxBillRate =
                        "Maximum Bill Rate must be greater than or equal to Minimum Bill Rate";
                    console.log("Max less than min error:", this.errors);
                    return;
                }

                this.entries.push({
                    ...this.newEntry
                });
                this.newEntry = {
                    jobLevel: "",
                    minBillRate: "",
                    maxBillRate: "",
                    currency: "",
                };
                console.log("New entry added:", this.entries);

                this.$dispatch("rate-card-updated", this.entries);

                this.newEntry = {
                    jobLevel: "",
                    minBillRate: "",
                    maxBillRate: "",
                    currency: "",
                };
            }
        },
        deleteEntry(index) {
            this.entries.splice(index, 1);
        },
        resetEntries() {
            this.entries = [];
            this.newEntry = {
                jobLevel: "",
                minBillRate: "",
                maxBillRate: "",
                currency: "",
            };
            this.errors = {
                minBillRate: "",
                maxBillRate: "",
            };
            this.showErrors = false;
        },
        formatCurrency(field) {
            let value = this.newEntry[field].replace(/[^\d.]/g, "");
            let parts = value.split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (parts.length > 1) {
                parts[1] = parts[1].slice(0, 2);
            }
            this.newEntry[field] = parts.join(".");
        },
        formatCurrencyDisplay(value, currency) {
            const symbol = this.getCurrencySymbol(currency);
            return `${symbol}${value}`;
        },
        getCurrencySymbol(currency) {
            const symbols = {
                USD: "$",
                EUR: "€",
                GBP: "£",
            };
            return symbols[currency] || "";
        },
        getCurrencyIcon(currency) {
            const icons = {
                USD: "fas fa-dollar-sign",
                EUR: "fas fa-euro-sign",
                GBP: "fas fa-pound-sign",
            };
            return icons[currency] || "fas fa-money-bill-alt";
        },
        isValid() {
            return this.entries.length > 0;
        },
    };
}
</script>
@endsection
