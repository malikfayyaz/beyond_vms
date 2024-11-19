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
                    <h2 class="text-lg font-medium">Timesheet Details</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Contractor Name:</span>
                            <span>Sree Praveen</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Timesheet Week:</span>
                            <span>11/17/2024 to 11/23/2024</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Timesheet Approved on:</span>
                            <span></span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Date of Submission:</span>
                            <span></span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Assignment ID:</span>
                            <span>4952</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Created From:</span>
                            <span></span>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Location:</span>
                            <span>US Rolling Meadows 2850 Golf Road</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Approving Manager:</span>
                            <span>Satyanarayana Kamasani</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Timesheet Type:</span>
                            <span>Standard (Per Hour)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Job Profile:</span>
                            <span class="flex-1">Statement of Work - SOW-TCS-000023-HomeOffice-CloudEngineer2
                                (4660)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row">
                            <span class="w-48 text-gray-600">Job Rate Type:</span>
                            <span>Per Hour</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-data="{
                    regularTime: Array(7).fill(0),
                    overTime: Array(7).fill(0),
                    days: [
                        'Sunday (11/17/2024)',
                        'Monday (11/18/2024)',
                        'Tuesday (11/19/2024)',
                        'Wednesday (11/20/2024)',
                        'Thursday (11/21/2024)',
                        'Friday (11/22/2024)',
                        'Saturday (11/23/2024)'
                    ],
                    calculateRowTotal(type) {
                        return type.reduce((sum, val) => sum + (parseFloat(val) || 0), 0).toFixed(2);
                    },
                    calculateDayTotal(index) {
                        return ((parseFloat(this.regularTime[index]) || 0) + (parseFloat(this.overTime[index]) || 0)).toFixed(2);
                    },
                    calculateGrandTotal() {
                        return (parseFloat(this.calculateRowTotal(this.regularTime)) + 
                               parseFloat(this.calculateRowTotal(this.overTime))).toFixed(2);
                    }
                }" class="p-4">
            <div class="bg-white rounded-lg shadow p-6">
                <!-- Timesheet Table -->
                <div class="overflow-x-auto">
                    <h4 class="mb-4 text-green-500">Note : Please enter time in the format of Decimal.</h4>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border p-2 bg-gray-50">Type</th>
                                <template x-for="(day, index) in days" :key="index">
                                    <th class="border p-2 bg-gray-50">
                                        <span x-text="day.split(' ')[0]"></span>
                                        <div class="text-xs text-gray-500" x-text="day.split(' ')[1]"></div>
                                    </th>
                                </template>
                                <th class="border p-2 bg-gray-50">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Regular Time -->
                            <tr>
                                <td class="border p-2">Regular Time</td>
                                <template x-for="(_, index) in regularTime" :key="index">
                                    <td class="border p-2">
                                        <input type="number" x-model="regularTime[index]"
                                            class="w-full p-1 border rounded text-center" step="0.1">
                                    </td>
                                </template>
                                <td class="border p-2 bg-gray-100 text-center font-medium"
                                    x-text="calculateRowTotal(regularTime)"></td>
                            </tr>
                            <!-- Over Time -->
                            <tr>
                                <td class="border p-2">Over Time</td>
                                <template x-for="(_, index) in overTime" :key="index">
                                    <td class="border p-2">
                                        <input type="number" x-model="overTime[index]"
                                            class="w-full p-1 border rounded text-center" step="0.1">
                                    </td>
                                </template>
                                <td class="border p-2 bg-gray-100 text-center font-medium"
                                    x-text="calculateRowTotal(overTime)"></td>
                            </tr>
                            <!-- Daily Totals -->
                            <tr>
                                <td class="border p-2">Total</td>
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
                    <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Submit Timesheet
                    </button>
                    <button class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Save Timesheet
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection