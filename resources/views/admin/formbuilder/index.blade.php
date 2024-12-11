@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
            @include('admin.layouts.partials.alerts')
            
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Forms</h2>
                    <div class="flex space-x-2">
                     <button
                         type="button"
                         class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                         onclick="window.location.href='{{ route('admin.formbuilder') }}'"
                     >
                         Create New Form
                     </button>
                 </div>
                </div>
                <table id="forms-table" class="table-auto w-full text-left border border-gray-300">
                    <thead  class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.$) {
            initializeDataTable('#forms-table', '/admin/formbuilder/index', [
                { data: 'id', name: 'id' },
                { data: 'type', name: 'type' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]);
        }
    });


</script>
@endsection
