@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            @include('admin.layouts.partials.alerts')
            <div id="success-message" style="display: none;" class="alert alert-success"></div>
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Offers</h2>
                </div>
                <div class="mb-4">
                 <ul
                     class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
                 >
                
                    <li class="flex justify-center">
                        <a
                            href="#all_offers"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="all_offers"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">All</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['all_offers'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#pending"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="pending"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">Pending</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['pending'] }}</span>
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center">
                        <a
                            href="#approved"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="approved"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">Approved</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['approved'] }}</span>
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
                    
                    
                 </ul>
                </div>
                <x-job-details />
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
                            Offer ID
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
                            Offer Date
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Bill Rate
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Workorder Status
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
            let currentType = 'all_offers';
            let currentId = '';
            let subId = '';
            let table = initializeDataTable('#listing', '/admin/offer/index', [
                { data: 'status', name: 'status' },
                { data: 'id', name: 'id' },
                { data: 'consultant_name', name: 'consultant_name' },
                { data: 'career_opportunity', name: 'career_opportunity' },
                { data: 'hiring_manger', name: 'hiring_manger' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'offer_bill_rate', name: 'offer_bill_rate' },
                { data: 'wo_status', name: 'wo_status' },
                { data: 'worker_type', name: 'worker_type' },
                { data: 'action', name: 'action', orderable: false, searchable: false }

            ], () => ({ currentType, currentId, subId }));

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

            $(document).on('click', '.tab-link', function(e) {
                e.preventDefault();

                $('.tab-link').removeClass('active-tab');
                $('.tab-link').removeClass('px-1 py-1 flex items-center justify-center text-white rounded-lg bg-primary');
                $('.tab-link').addClass('w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow');
                $(this).addClass('px-1 py-1 flex items-center justify-center text-white rounded-lg bg-primary');
                $(this).addClass('active-tab');
                // Update currentType variable if needed
                currentType = $(this).data('type');
                console.log(currentType);
                
                window.location.hash = $(this).attr('href');
                table.ajax.reload();
                
            });
        }
    });
</script>
@endsection
