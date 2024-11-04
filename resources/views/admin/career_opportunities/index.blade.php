@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16" >
    @include('admin.layouts.partials.header')
       <div class="bg-white mx-4 my-8 rounded p-8"  x-data="{ selectedUser: null}" @job-details-updated.window="selectedUser = $event.detail">
           @include('admin.layouts.partials.alerts')
           <div id="success-message" style="display: none;" class="alert alert-success"></div>
           <div >
             <div class="flex justify-between items-center mb-6">
                 <h2 class="text-2xl font-bold">Jobs</h2>
                 <div class="flex space-x-2">
                     <button
                         type="button"
                         class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                         onclick="window.location.href='{{ route('admin.career-opportunities.create') }}'"
                     >
                         Create New Job
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
                            <span class="capitalize">Active Jobs</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">156</span>
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
                            <span class="capitalize">Pending Release Job</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg bg-primary">
                                <span class="text-[10px]">56</span>
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
                            <span class="capitalize">Filled Jobs</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">20</span>
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
                            <span class="capitalize">Closed Jobs</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">20</span>
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
                            <span class="capitalize">Pending - PMO</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">20</span>
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
                            <span class="capitalize">All Jobs</span>
                            <div class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg">
                                <span class="text-[10px]">20</span>
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

                    function toggleSidebar() {
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

                      // Handle Tab Click Events
                      $(document).on('click', '.tab-link', function(e) {
            e.preventDefault();

            // Remove active classes from all tabs
            $('.tab-link').removeClass('active-tab');

            // Add active class to the clicked tab
            $(this).addClass('active-tab');

            // Update currentType based on clicked tab's data-type
            currentType = $(this).data('type');
            // console.log(currentType);
            
            // Optionally, update the URL fragment
            window.location.hash = $(this).attr('href');

            // Reload the DataTable with the new type
            // table.ajax.reload();
            table.ajax.reload(null, false);
        });

        // Optionally, trigger click on the default tab to load initial data
        // $('.tab-link[data-type="active"]').trigger('click');

       
                  
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
