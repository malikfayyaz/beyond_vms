@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null, submissionDetails: null }" @job-details-updated.window="jobDetails = $event.detail"
        @submission-details-updated.window="submissionDetails = $event.detail">
        
            <div id="success-message" style="display: none;" class="alert alert-success"></div>
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Submission</h2>
                </div>
             <div class="mb-4">
                 <ul
                     class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
                 >
                
                    <li class="flex justify-center">
                        <a
                            href="#active"
                            class="tab-link w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                            data-type="active"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                            <span class="capitalize">Active</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['active'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#submitted"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="submitted"
                        >
                            <i class="fa-regular fa-registered"></i>
                            <span class="capitalize">Submitted</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['submitted'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#shortlisted"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="shortlisted"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">Shortlisted</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['shortlisted'] }}</span>
                            </div>
                        </a>
                    </li>


                    <li class="flex justify-center">
                        <a
                            href="#rejected"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="rejected"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">Rejected</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['rejected'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#review"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="review"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">Client Review</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['pending_pmo'] }}</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="flex justify-center">
                        <a
                            href="#all_subs"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="all_subs"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">All</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['all_subs'] }}</span>
                            </div>
                        </a>
                    </li>
                 </ul>
             </div>

                <x-job-details />
                
                <x-submission-details />

                <table class="min-w-full divide-y divide-gray-200" id="listing">
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
                            Submission ID
                        </th>
                        <!-- job -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Candidate Name
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Unique ID
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job Profile
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Hiring Manager
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Vendor
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Pay Rate
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Bill Rate
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Location
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
        if (window.$) {
            initializeDataTable('#listing', '/admin/submission/index', [
                { data: 'resume_status', name: 'resume_status' },
                { data: 'id', name: 'id' },
                { data: 'consultant_name', name: 'consultant_name' }, // Consultant name
                { data: 'unique_id', name: 'unique_id' },
                { data: 'career_opportunity_title', name: 'career_opportunity_title' },
                { data: 'hiring_manager_name', name: 'hiring_manager_name' },
                { data: 'vendor_name', name: 'vendor_name' }, // Vendor name
                { data: 'candidate_pay_rate', name: 'candidate_pay_rate' },
                { data: 'bill_rate', name: 'bill_rate' },
                { data: 'location_name', name: 'location_name' }, // Location name
                { data: 'worker_type', name: 'worker_type' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]);

            function toggleSidebar() 
            {
                // Assuming you want to toggle selectedUser state
                this.selectedUser = this.selectedUser ? 'user' : 'user';
            }

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

            $(document).on('click', '.submission-detail-trigger', function (e) {
                e.preventDefault();
                let submissionId = $(this).data('id');
                openSubmissionDetailsModal(submissionId);
            });

            function openSubmissionDetailsModal(submissionId) {
                console.log(submissionId);
                fetch(`/submission-details/${submissionId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        const event = new CustomEvent('submission-details-updated', {
                            detail: data,
                            bubbles: true,
                            composed: true
                        });
                        document.dispatchEvent(event);
                    })
                    .catch(error => console.error('Error fetching submission details:', error));
            }
        }
    });
</script>
@endsection
