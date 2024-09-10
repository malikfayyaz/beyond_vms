@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
        @include('admin.layouts.partials.alerts')
            <div>
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold">Admin Management</h3>

                    <!-- Add User Button -->
                    <button
                        class="px-4 py-2 bg-blue-500 capitalize text-white rounded disabled:opacity-50 ml-2"
                        :style="{'background-color': 'var(--primary-color)'}"
                        onclick="window.location.href='{{ route('admin.admin-users.create') }}'" 
                    >
                        Add New Admin
                    </button>
                </div>

                <!-- Users Table -->
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden data-table" id="usersTable">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left cursor-pointer">First Name</th>
                            <th class="py-3 px-4 text-left cursor-pointer">Last Name</th>
                            <th class="py-3 px-4 text-left cursor-pointer">Email</th>
                            <th class="py-3 px-4 text-left cursor-pointer">Role</th>
                            <th class="py-3 px-4 text-left cursor-pointer">Status</th>
                            <th class="py-3 px-4 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTable -->
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.$) {
                // Initialize DataTable for users
                initializeDataTable('#usersTable', '{{ route('admin.admin-users.index') }}', [
                    { data: 'id', name: 'id' },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]);
            }
        });

        
    </script>
@endsection
