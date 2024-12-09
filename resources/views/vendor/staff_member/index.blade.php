@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            @include('vendor.layouts.partials.alerts')
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">{{ translate('List of Staff Members') }}</h2>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{ route('vendor.staffmember.create') }}'"
                        >
                            {{translate('Create New Staff Member')}}
                        </button>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200" id="listing">
                    <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('S.No') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('Name') }}
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('Email') }}
                        </th>
{{--
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('Staff Type') }}
                        </th>
--}}
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('status') }}
                        </th>
                        <th style="width: 80px"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ translate('Action') }}
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.$) { // Check if jQuery ($) is available
                let table = initializeDataTable('#listing', '/vendor/staffmember/index', [
                    { data: 'id', name: 'id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'email', name: 'email' },
                    { data: 'profile_status', name: 'profile_status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]);
            }
        });
    </script>
@endsection
