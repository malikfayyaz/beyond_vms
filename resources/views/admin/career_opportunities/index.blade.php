@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
       <div class="bg-white mx-4 my-8 rounded p-8">
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
                             href="#page1"
                             class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                         >
                             <i class="fa-regular fa-file-lines"></i>
                             <span class="capitalize">active jobs</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">156</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center items-center">
                         <a
                             href="#page2"
                             class="w-full flex justify-center items-center gap-3 bg-white rounded-lg shadow py-4"
                             :style="{'color': 'var(--primary-color)'}"
                         ><i class="fa-regular fa-registered"></i
                             ><span class="capitalize">Pending Release Job</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                 :style="{'background-color': 'var(--primary-color)'}"
                             >
                                 <span class="text-[10px]">56</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center">
                         <a
                             href="#page1"
                             class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                         >
                             <i class="fa-solid fa-fill"></i>
                             <span class="capitalize">filled jobs</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">20</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center">
                         <a
                             href="#page1"
                             class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                         >
                             <i class="fa-solid fa-lock"></i>
                             <span class="capitalize">closed jobs</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">2957</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center">
                         <a
                             href="#page1"
                             class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                         >
                             <i class="fa-solid fa-spinner"></i>
                             <span class="capitalize">pending - PMO</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">0</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center">
                         <a
                             href="#page1"
                             class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                         >
                             <i class="fas fa-drafting-compass"></i>
                             <span class="capitalize">draft</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">30</span>
                             </div>
                         </a>
                     </li>
                     <li class="flex justify-center">
                         <a
                             href="#page1"
                             class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                         >
                             <i class="fa-solid fa-briefcase"></i>
                             <span class="capitalize">all jobs</span>
                             <div
                                 class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                             >
                                 <span class="text-[10px]">4320</span>
                             </div>
                         </a>
                     </li>
                 </ul>
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

       <!-- Background Overlay -->
        <div id="flyoutOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Job Flyout -->
        <div id="jobFlyout" class="hidden fixed right-0 top-0 w-1/3 h-full bg-white shadow-lg z-50 p-3">
            <!-- Flyout content here -->
            <button id="closeFlyout" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 bg-transparent">close</button>
            <h3 class="text-xl font-semibold mb-4 mt-4">Job Details</h3>
            <div id="flyoutContent">
                <!-- Job details will be dynamically inserted here -->
            </div>
        </div>
</div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {
                 console.log(window.$); // Verify jQuery is available
                if (window.$) {

                    initializeDataTable('#example', '/admin/career-opportunities', [
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
                    ]);

                     // Open flyout on job detail click
                    $('#example').on('click', '.job-detail-trigger', function() {
                        const jobId = $(this).data('id');
                        openJobFlyout(jobId);
                        $('#jobFlyout').removeClass('hidden').addClass('show');
                        $('#flyoutOverlay').removeClass('hidden').addClass('show');
                    });

                    // Close flyout
                    $('#closeFlyout').on('click', function() {
                        $('#jobFlyout').addClass('hidden');
                        $('#flyoutOverlay').addClass('hidden');
                    });
                }

      });
   </script>
    <script>
        function openJobFlyout(jobId) {
            // Fetch job data from the server
            $.ajax({
                url: `/job/${jobId}/flyout`,
                method: 'GET',
                success: function(response) {
                    console.log(response); // Verify jQuery is available

                    // Populate flyout content with job details
                    $('#flyoutContent').html(`
                        <p><strong>Job ID:</strong> ${response.id}</p>
                        <p><strong>Title:</strong> ${response.title}</p>
                        <p><strong>Hiring Manager:</strong> ${response.hiring_manager || 'N/A'}</p>
                        
                        <!-- Add other fields as needed -->
                    `);
                    // Show the flyout
                    $('#jobFlyout').removeClass('hidden');
                }
            });
        }
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
