@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            @include('client.layouts.partials.alerts')
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Contract</h2>
                </div>

                <x-job-details />

                <table class="min-w-full divide-y divide-gray-200" id="listing">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultant</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Career Opportunity(ID)
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hiring Manager</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Vendor
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Duration
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Worker Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th style="width: 80px" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.$) {
                initializeDataTable('#listing', '{{ route("client.contracts.index") }}', [
                    { data: 'status', name: 'status' },
                    { data: 'id', name: 'id' },
                    { data: 'consultant_name', name: 'consultant_name' },
                    { data: 'career_opportunity', name: 'career_opportunity' },
                    { data: 'hiring_manager', name: 'hiring_manager' },  // Ensure correct spelling
                    { data: 'vendor_name', name: 'vendor_name' },
                    { data: 'duration', name: 'duration' },
                    { data: 'worker_type', name: 'worker_type' },
                    { data: 'location', name: 'location' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
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
