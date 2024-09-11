@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
@include('admin.layouts.partials.header')
       <div class="bg-white mx-4 my-8 rounded p-8">
         <div >
           <div class="mb-4 flex justify-between items-center">
             <div class="w-full flex items-center justify-between">

               <div>
                 <button
                   class="px-4 py-2 bg-blue-500 capitalize text-white rounded disabled:opacity-50 ml-2"
                   :style="{'background-color': 'var(--primary-color)'}"
                   onclick="window.location.href='{{ route('admin.career-opportunities.create') }}'"
                 >
                   add job 
                 </button>
               </div>
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
                          <th
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
                  initializeDataTable('#example', '/admin/career-opportunities', [
                    { data: 'jobStatus', name: 'jobStatus' },
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'alternative_job_title', name: 'alternative_job_title' },
                    { data: 'hiring_manager', name: 'hiring_manager' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'num_openings', name: 'num_openings' },
                    { data: 'worker_type_id', name: 'worker_type_id' },
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
