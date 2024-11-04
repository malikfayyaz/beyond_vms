@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('vendor.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Jobs</h2>
                    <div class="flex space-x-2">
                        {{--<button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{ route('vendor.career-opportunities.create') }}'"
                        >
                            Create New Job
                        </button>--}}
                    </div>
                </div>

                <x-job-details />

                <table class="min-w-full divide-y divide-gray-200" id="example">
                    <thead class="bg-gray-50">
                    <tr>
                        <!-- Status -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <!-- User -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job ID
                        </th>
                        <!-- job -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job title
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job Title for Email Signature
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Hiring Manager
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job Duration
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Submissions
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Position
                        </th>


                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Worker Type
                        </th>
                        <th style="width: 80px"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Action
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
            console.log(window.$); // Verify jQuery is available
            if (window.$) {
                initializeDataTable('#example', '/vendor/career-opportunities', [
                    { data: 'jobStatus', name: 'jobStatus' },
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'alternative_job_title', name: 'alternative_job_title' },
                    { data: 'hiring_manager', name: 'hiring_manager' },
                    { data: 'duration', name: 'duration' },
                    { data: 'submissions', name: 'submissions' },
                    { data: 'num_openings', name: 'num_openings' },
                    { data: 'worker_type', name: 'worker_type' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]);

                $(document).on('click', '.job-detail-trigger', function (e) {
                    e.preventDefault();
                    let jobId = $(this).data('id');
                    openJobDetailsModal(jobId);
                });

                function openJobDetailsModal(jobId) {
                    
                    fetch(`/job-details/${jobId}`)
                            .then(response => response.json())
                            .then(data => {
                                const event = new CustomEvent('job-details-updated', {
                                        detail: data,
                                        bubbles: true,
                                        composed: true
                                    });
                                    console.log(event.detail.data);
                                    
                                    document.dispatchEvent(event);
                            })
                            .catch(error => console.error('Error:', error));

                }
            }

        });
    </script>
@endsection
