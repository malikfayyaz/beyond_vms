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
                    <h3 class="text-xl font-bold">{{translate('Vendor Management')}}</h3>

                    <!-- Add Vendor Button -->
                    <button
                        class="px-4 py-2 bg-blue-500 capitalize text-white rounded disabled:opacity-50 ml-2"
                        :style="{'background-color': 'var(--primary-color)'}"
                        onclick="window.location.href='{{ route('admin.vendor-users.create') }}'"
                    >
                        {{translate('Add New Vendor')}}
                    </button>
                </div>

                <!-- Vendors Table -->
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden data-table" id="vendorsTable">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left cursor-pointer">{{translate('Full Name')}}</th>
                            <th class="py-3 px-4 text-left cursor-pointer">{{translate('Email')}}</th>
                            <th class="py-3 px-4 text-left cursor-pointer">{{translate('Status')}}</th>
                            <th class="py-3 px-4 text-left">{{translate('Action')}}</th>
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
                // Initialize DataTable for vendors
                initializeDataTable('#vendorsTable', '{{ route('admin.vendor-users.index') }}', [
                    { data: 'id', name: 'id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'email', name: 'email' },
                    { data: 'profile_status', name: 'profile_status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]);
            }
        });
    </script>
@endsection
