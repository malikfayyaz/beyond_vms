@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="workflowTable()">
            <div class="container mx-auto p-4">
                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">{{translate('Sr #')}}Sr #</th>
                            <th class="border p-2">{{translate('Clients')}}</th>
                            <th class="border p-2">{{translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <tr>
                                <td class="border p-2" x-text="index + 1"></td>
                                <td class="border p-2" x-text="item.full_name ?? 'N/A'"></td>
                                <td class="border p-2">
                                    <span @click="editItem(item)" class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            <div>
        </div>
    </div>

    <script>
        function workflowTable() {
            return {
                // Initialize with data passed from the server
                filteredItems: @json($data),

                // Method to handle edit item action
                editItem(item) {
                    console.log('Editing item:', item.id);
                    let redirect_url = '{{ route('admin.workflow.create', ':id') }}'.replace(':id', item.id);
                    window.location.href = redirect_url;
                    // Add logic to handle editing
                }
            }
        }
    </script>
@endsection
