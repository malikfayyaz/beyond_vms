@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div >
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Added Permissions</h2>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{ route('permissions.create') }}'"
                        >
                            Create New Permissions
                        </button>
                        <button
                            type="button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                            onclick="window.location.href='{{ route('admin.dashboard') }}'"
                        >
                            Back to Dashboard
                        </button>
                    </div>
                </div>
                <table
                    class="min-w-full bg-white shadow-md rounded-lg overflow-hidden data-table" id="example"
                >
                    <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th

                            class="py-3 px-4 text-left cursor-pointer"
                        >
                            Permission Name

                        </th>
                       <th class="py-3 px-4 text-left">Action</th>
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
                initializeDataTable('#example', '/permissions', [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]);
            }

        });
    </script>
@endsection
