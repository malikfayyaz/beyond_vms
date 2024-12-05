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
                   onclick="window.location.href='{{ route('admin.catalog.create') }}'"
                 >
    {{translate('Add Job Catalog')}}
  </button>
</div>
</div>
</div>
<table
class="min-w-full bg-white shadow-md rounded-lg overflow-hidden data-table" id="example"
>
<thead class="bg-gray-200 text-gray-700">
<tr>
  <th class="py-3 px-4 text-left">{{translate('ID')}}</th>
  <th

    class="py-3 px-4 text-left cursor-pointer"
  >
    {{translate('Catalog Name')}}

  </th>
  <th

    class="py-3 px-4 text-left cursor-pointer"
  >
    {{translate('Category')}}

  </th>
  <th

    class="py-3 px-4 text-left cursor-pointer"
  >
    {{translate('Profile Worker Type')}}

  </th>
  <th

    class="py-3 px-4 text-left cursor-pointer"
  >
    {{translate('Status')}}

  </th>
  <th class="py-3 px-4 text-left">{{translate('Action')}}</th>
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
   initializeDataTable('#example', '/admin/job/catalog', [
     { data: 'id', name: 'id' },
     { data: 'job_title', name: 'job_title' },
     { data: 'category', name: 'category' },
     { data: 'profile_worker_type', name: 'profile_worker_type' },
     { data: 'status', name: 'status' },
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
