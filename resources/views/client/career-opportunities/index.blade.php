@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Jobs</h2>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{ route('client.career-opportunities.create') }}'"
                        >
                            Create New Job
                        </button>
                    </div>
                </div>
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
                initializeDataTable('#example', '/client/career-opportunities', [
                    { data: 'jobStatus', name: 'jobStatus' },
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'alternative_job_title', name: 'alternative_job_title' },
                    { data: 'hiring_manager', name: 'hiring_manager' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            if (data.start_date && data.end_date) {
                                let startDate = new Date(data.start_date);
                                let endDate = new Date(data.end_date);

                                // Format the dates as MM/DD/YYYY
                                let formatDate = function(date) {
                                    let day = String(date.getDate()).padStart(2, '0');
                                    let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                                    let year = date.getFullYear();
                                    return `${month}/${day}/${year}`;
                                };

                                return `${formatDate(startDate)} - ${formatDate(endDate)}`;
                            }
                            return 'N/A';
                        },
                        name: 'duration' // Column name for DataTable
                    },
                    { data: 'num_openings', name: 'num_openings' },
                    { data: 'worker_type', name: 'worker_type' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]);
            }

        });
    </script>
    <script>
        function deleteItem(url) {
            console.log(window.$); // Verify jQuery is available
            if (confirm('Are you sure you want to delete this record?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Record deleted successfully');
                            // Reload the DataTable to reflect changes
                            $('#example').DataTable().ajax.reload();
                        } else {
                            alert('Error deleting record');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting record');
                    });
            }
        }
    </script>

@endsection
