@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ selectedUser: null}" @job-details-updated.window="selectedUser = $event.detail">
            @include('client.layouts.partials.alerts')
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Interviews</h2>
                </div>

                <x-job-details />

                <table class="min-w-full divide-y divide-gray-200" id="listing">
                    <thead class="bg-gray-50">
                    <tr>
                        <!-- Status -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            S. no.
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <!-- User -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Type
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            ID
                        </th>
                        <!-- job -->
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Contractor Name
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Job Profile
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Hiring Manger
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Vendor
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Date
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Start Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            End Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Woker Type
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
            initializeDataTable('#listing', '/client/interview/index', [
                {
                data: null,
                    name: 'serial',
                    render: function (data, type, full, meta) {
                        return meta.row + 1;  // Display the row index (1-based)
                    }
                },
                { data: 'status', name: 'status' },
                { data: 'type', name: 'type' },
                { data: 'id', name: 'id' },
                { data: 'consultant_name', name: 'consultant_name' },
                { data: 'career_opportunity', name: 'career_opportunity' },
                { data: 'hiring_manger', name: 'hiring_manger' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'primary_date', name: 'primary_date' },
                { data: 'primary_start_time', name: 'primary_start_time'},
                { data: 'primary_end_time', name: 'primary_end_time'},
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
        }
    });
</script>
@endsection
