@extends('vendor.layouts.app')

@section('content')
<!-- Sidebar -->
@include('vendor.layouts.partials.dashboard_side_bar')
@php

$timesheetStartDate = $contract->start_date;
$startDay = 0;
$submittedTimesheets = [];
@endphp
<div class="ml-16">
    @include('vendor.layouts.partials.header')
    <div class="rounded border p-[30px]">
        <div class="w-100 p-[30px] rounded border" :style="{'border-color': 'var(--primary-color)'}"
            x-data="contractor({{ 'null' }})">
            <div class="container mx-auto p-4">
                <div class="flex flex-wrap mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="timesheet_duration" class="block mb-2">Select Month & Week <span class="text-red-500">*</span></label>
                        <select id="timesheet_duration" name="timesheet_duration" class="w-full p-2 border rounded h-10 bg-white"
                            x-model="formData.timesheet_duration">
                            <option value="" disabled>Select Month & Week</option>
                            <?php 
                            for($i=0; $i<=9; $i++){
                          
                                $y = ($i * 7);
                                $weekNumber = date('W',strtotime('-'.$i.' week'));
                                if($weekNumber==53){
                                    continue;
                                }
                                $year = date('Y',strtotime('-'.$y.' days'));
                                $month = date('F',strtotime('+'.$i.' week'));
                                $dates = timesheetGetStartAndEndDate($weekNumber, $year ,$startDay);
                                if(strtotime($timesheetStartDate) <= strtotime($dates[1])){
                                    if((strtotime($dates[0]) <= strtotime($contract->end_date) && $contract->termination_status != 2 && $contract->status != 3) || (($contract->termination_status == 2 || $contract->status == 3) && strtotime($contract->termination_date) >= strtotime($dates[0]))) {

                                        if (strtotime($timesheetStartDate) >= strtotime($dates[0])) {
                                            $firstDate = date('Y-m-d', strtotime($timesheetStartDate));
                                            //$firstDateShow = date('m/d/Y', strtotime($timesheetStartDate));
                                            $firstDateShow = formatDate($timesheetStartDate);
                                        } else {
                                            $firstDate = date('Y-m-d', strtotime($dates[0]));
                                            //$firstDateShow = date('m/d/Y', strtotime($dates[0]));
                                            $firstDateShow = formatDate($dates[0]);
                                        }
                                        if (($contract->termination_status == 2 || $contract->status == 3) && strtotime($contract->termination_date) < strtotime($dates[1])) {
                                            $secondDate = date('Y-m-d', strtotime($contract->termination_date));
                                            //$secondDateShow = date('m/d/Y', strtotime($contract->termination_date));
                                            $secondDateShow =  formatDate($contract->termination_date);
                                        } else if (strtotime($contract->end_date) < strtotime($dates[1])) {
                                            $secondDate = date('Y-m-d', strtotime($contract->end_date));
                                            ///$secondDateShow = date('m/d/Y', strtotime($contract->end_date));
                                            $secondDateShow = formatDate($contract->end_date);
                                        } else {
                                            $secondDate = date('Y-m-d', strtotime($dates[1]));
                                            ///$secondDateShow = date('m/d/Y', strtotime($dates[1]));
                                            $secondDateShow = formatDate($dates[1]);
                                        }

                                        $firstDatePlusOneDay = date('Y-m-d', strtotime($firstDate . ' +1 day'));
                                        if (!in_array(strtotime($firstDate), $submittedTimesheets) && !in_array(strtotime($firstDatePlusOneDay), $submittedTimesheets)) {
                                            echo '<option value="'.$firstDate.'/'.$secondDate.'">'.date('F',strtotime($firstDate)).' ( '.$firstDateShow.' to '.$secondDateShow.' )</option>';
                                        }
                                    }
                                }
                            }
                            ?>

                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="timesheet_durationError"></p>
                    </div>
                    <div class="flex mb-4">
                        <button @click="submitData1()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Continue
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.$) {
       
    }
});

function contractor(editIndex) {

    return {
        formData: {
            timesheet_duration: "",
            contract_id:`{{$contract->id}}`,
        },
        timesheet_durationError: "",
        editIndex: editIndex,
        searchTerm: "",
        error: 0,
        currentUrl: `{{ url()->current() }}`,

        validateFields() {
            this.error = 0;

            if (this.formData.timesheet_duration === "") {
                this.timesheet_durationError = `Please select Contractor`;
                this.error += 1;
            } else {
                this.timesheet_durationError = "";
            }
        },

        submitData1() {
            this.validateFields();
            if (this.error === 0) {
                let formData = new FormData();
                formData.append('timesheet_duration', this.formData.timesheet_duration);
                formData.append('contract_id', this.formData.contract_id);
                
                url = '{{ route("vendor.timesheet.step_one_store") }}';

                ajaxCall(url, 'POST', [
                    [onSuccess, ['response']]
                ], formData);
            }
        }
    };
}
</script>