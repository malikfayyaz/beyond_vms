@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16" >
    @include('admin.layouts.partials.header')

       <div class="bg-white mx-4 my-8 rounded p-8"  x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
           @include('admin.layouts.partials.alerts')
           <div id="success-message" style="display: none;" class="alert alert-success"></div>
           <div >
             <div class="flex justify-between items-center mb-6">
                 <h2 class="text-2xl font-bold">{{translate('Jobs')}}</h2>
                 <div class="flex space-x-2">
                     <button
                         type="button"
                         class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                         onclick="window.location.href='{{ route('admin.career-opportunities.create') }}'"
                     >
                         {{translate('Create New Job')}}
                     </button>
                 </div>
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
                            <span class="capitalize">{{translate('Active Jobs')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['active'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#open"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="open"
                        >
                            <i class="fa-regular fa-registered"></i>
                            <span class="capitalize">{{translate('Pending Release Job')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['open'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#filled"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="filled"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">{{translate('Filled Jobs')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['filled'] }}</span>
                            </div>
                        </a>
                    </li>


                    <li class="flex justify-center">
                        <a
                            href="#closed"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="closed"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">{{translate('Closed Jobs')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['closed'] }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center">
                        <a
                            href="#pendingpmo"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="pendingpmo"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">{{translate('Pending - PMO')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['pending_pmo'] }}</span>
                            </div>
                        </a>
                    </li>

                    <li class="flex justify-center">
                        <a
                            href="#all_jobs"
                            class="tab-link w-full flex justify-center items-center gap-3 py-4 hover:bg-white hover:rounded-lg hover:shadow"
                            data-type="all_jobs"
                        >
                            <i class="fa-solid fa-fill"></i>
                            <span class="capitalize">{{translate('All Jobs')}}</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">{{ $counts['all_jobs'] }}</span>
                            </div>
                        </a>
                    </li>
                 </ul>
             </div>

            <x-job-details />
           <table class="min-w-full divide-y divide-gray-200" id="example">
                      <thead class="bg-gray-50">
                        <tr>
                          <!-- Status -->
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Status')}}
                          </th>
                          <!-- User -->
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Job ID')}}
                          </th>
                          <!-- job -->
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Job title')}}
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Job Title for Email Signature')}}
                          </th>

                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Hiring Manager')}}
                          </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Job Duration')}}
                          </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{translate('Submissions')}}
                            </th>
                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Position')}}
                          </th>


                          <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Worker Type')}}
                          </th>
                          <th style="width: 80px"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                              {{translate('Action')}}
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
                    let currentType = 'active';
                    let table =initializeDataTable('#example', '/admin/career-opportunities', [
                        { data: 'jobStatus', name: 'jobStatus' },
                        { data: 'id', name: 'id' },
                        { data: 'title', name: 'title' },
                        { data: 'alternative_job_title', name: 'alternative_job_title' },
                        { data: 'hiring_manager', name: 'hiring_manager' },
                        { data: 'duration', name: 'duration' },
                        { data: 'submissions', name: 'submissions' }, // Make sure this matches the column name
                        { data: 'num_openings', name: 'num_openings' },
                        { data: 'worker_type', name: 'worker_type' },
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],  () => currentType);

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
    currentType = $(this).data('type');
    window.location.hash = $(this).attr('href');
    table.ajax.reload(null, false);
        });
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
