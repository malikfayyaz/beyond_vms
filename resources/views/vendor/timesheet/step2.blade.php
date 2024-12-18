@extends('vendor.layouts.app')

@section('content')
<!-- Sidebar -->
@include('vendor.layouts.partials.dashboard_side_bar')



<div class="ml-16">
    @include('vendor.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8">
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <h2 class="text-lg font-medium">{{translate('Timesheet Details')}}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Contractor Name:')}}</span>
                            <span>{{$contract->consultant->fullname}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Timesheet Week:')}}</span>
                            <span>{{formatDate($startDate)}} to {{formatDate($endDate)}}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Date of Submission:')}}</span>
                            <span>{{formatDate($contract->submission->shortlisted_date)}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Contract ID:')}}</span>
                            <span>{{$contract->id}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Created By:')}}</span>
                            <span>{{$contract->createdBy->name}}</span>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Location:')}}</span>
                            <span>{{locationName($contract->location_id)}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Approving Manager:')}}</span>
                            <span>{{$contract->HiringManager->full_name}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Timesheet Type:')}}</span>
                            <span>{{getSettingTitleById($contract->timesheet_type)}}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Job Profile')}}:</span>
                            <span class="flex-1">{{$contract->careerOpportunity->title}}({{$contract->careerOpportunity->id}})</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">{{translate('Job Rate Type:')}}</span>
                            <span>{{$contract->careerOpportunity->paymentType->title}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $diff = date_diff(date_create($startDate),date_create($endDate));
        $starttimestamp = strtotime($startDate);
        $days = $diff->format("%a");
        // dd($days);
        for($i = 0; $i <= $days; $i++ ){
            $dayDate =  date('l', strtotime('+' . $i . ' day', $starttimestamp)) . ' (' . formatDate(date('m/d/Y',strtotime('+' . $i . ' day', $starttimestamp))).')';
            $dayCheck= date('l', strtotime('+' . $i . ' day', $starttimestamp));
            $dayStrTime= date('Y-m-d',strtotime('+' . $i . ' day', $starttimestamp));
            $dimColorClass = ($dayCheck == 'Saturday' || $dayCheck == 'Sunday' ) ? 'dim-color' : '';
            $daysArray[] = "".$dayDate."";
            $regularTimeArray[] = [
                'name' => "days[{$dayDate}][]",
                'id' => date('D', strtotime('+' . $i . ' day', $starttimestamp)) ,
                'class' => "form-control tsregulardays_" . date('D', strtotime('+' . $i . ' day', $starttimestamp)) . " tsregulardays {$dimColorClass}",
                'value' => 0,
            ];
        }
        // Encode the array as JSON
        $daysJson = json_encode($daysArray);
        $regularTime = json_encode($regularTimeArray);
         ?>
        <div x-data="timesheetHandler" class="p-4">

            <div class="bg-white rounded-lg shadow p-6">
                <!-- Timesheet Table -->
                <div class="overflow-x-auto">
                    <h4 class="mb-4 text-green-500">{{translate('Note : Please enter time in the format of Decimal.')}}</h4>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border p-2 bg-gray-50">{{translate('Type')}}</th>
                                <template x-for="(day, index) in days" :key="index">
                                    <th class="border p-2 bg-gray-50">
                                        <span x-text="day.split(' ')[0]"></span>
                                        <div class="text-xs text-gray-500" x-text="day.split(' ')[1]"></div>
                                    </th>
                                </template>
                                <th class="border p-2 bg-gray-50">{{translate('Total')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Regular Time -->
                            <tr>
                                <td class="border p-2">{{translate('Regular Time')}}</td>
                                <template x-for="(input, index) in regularTimeInputs" :key="index">
                                <td class="border p-2">
                                                        <input type="number"
                                                            :name="input.name"
                                                            :id="input.id"
                                                            :class="input.class"
                                                            x-model="regularTime[index]"
                                                            min="0"
                                                            step="0.25"
                                                            :value="input.value"
                                                            class="w-full p-1 border rounded text-center">
                                                    </td>
                                </template>
                                <td class="border p-2 bg-gray-100 text-center font-medium"
                                    x-text="calculateRowTotal(regularTime)"></td>
                            </tr>
                            <!-- Over Time -->

                            <!-- Daily Totals -->
                            <tr>
                                <td class="border p-2">{{translate('Total')}}</td>
                                <template x-for="(_, index) in regularTime" :key="index">
                                    <td class="border p-2 bg-gray-100 text-center font-medium"
                                        x-text="calculateDayTotal(index)"></td>
                                </template>
                                <td class="border p-2 bg-gray-200 text-center font-medium"
                                    x-text="calculateGrandTotal()"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Buttons -->
                <div class="mt-6 flex gap-4">
                    <button @click="submitTimesheet" name="simpleTimesheet" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        {{translate('Submit Timesheet')}}
                    </button>
                    <button name="saveSheet" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        {{translate('Save Timesheet')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('timesheetHandler', () => ({
        selectedProject: [],
        regularTime: Array({{ count($regularTimeArray) }}).fill(0),
        regularTimeInputs: {!! $regularTime !!},
        days: {!! $daysJson !!},
        startdate:{{strtotime($startDate)}},
        enddate:{{strtotime($endDate)}},
        contract_id:`{{$contract->id}}`,
        errors: [],
        country_timesheet_sub_type:`{{$contract->careerOpportunity->payment_type}}`,
        projects: {!! $timesheetCostCenter->map(fn($proj) => $proj->buName->id)->toJson() !!},
        init() {
            // Automatically set the first project as selected, or adjust logic as needed
            if (this.projects.length > 0) {
                this.selectedProject = [...this.projects];
            }
        },
        calculateRowTotal(type) {
            return type.reduce((sum, val) => sum + (parseFloat(val) || 0), 0).toFixed(2);
        },

        calculateDayTotal(index) {
            return (parseFloat(this.regularTime[index]) || 0).toFixed(2);
        },

        calculateGrandTotal() {
            return this.calculateRowTotal(this.regularTime);
        },

        validateInputs() {


            this.errors = [];
            this.regularTime.forEach((value, index) => {
                if (value > 24) {
                    this.errors.push(`Day ${this.days[index]} exceeds 24 hours.`);
                }
            });
            return this.errors.length === 0;
        },

        submitTimesheet() {
            if (!this.validateInputs()) {
                document.getElementById('error_log').innerText = this.errors.join('\n');
                return;
            }

            let formData = new FormData();

            // Create a payload


            const payload = this.regularTimeInputs.map((input, index) => ({
                day: this.days[index].split('(')[1]?.replace(')', '').trim(),
                value: this.regularTime[index],
            }));
            // console.losg(payload);




            this.selectedProject.forEach((project) => {
                formData.append('projects[]', project);
            });
            formData.append('startdate', this.startdate);
            formData.append('enddate', this.enddate);
            formData.append('country_timesheet_sub_type', this.country_timesheet_sub_type);
            formData.append('timesheet', JSON.stringify(payload));
            formData.append('contract_id', this.contract_id);
            formData.append('type', 'submit');
            // Send AJAX request
            url = '{{ route("vendor.timesheet.step_two_store") }}';

                ajaxCall(url, 'POST', [
                    [onSuccess, ['response']]
                ], formData);

        },
    }));
});
</script>
