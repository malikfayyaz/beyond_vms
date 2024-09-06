@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Users</h2>
                <div class="flex space-x-2">
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                        onclick="window.location.href='{{ route('admin.dashboard') }}'"
                    >
                        Back to Dashboard
                    </button>
                </div>
            </div>

            <!-- User Table -->
            <div class="flex gap-8">
                <div class="p-8 bg-sky-100 rounded w-full">
                    <table class="min-w-full bg-white">
                        <thead style="border-bottom: 2px solid #ccc;">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th

                                class="py-3 px-4 text-left cursor-pointer"
                            >
                                User Name

                            </th>
                            <th class="py-3 px-4 text-left">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-300">{{ $user->id }}</td>
                                <td class="py-2 px-4 border-b border-gray-300">{{ $user->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-300">
                                    <a href="{{ route('users.assignRoleForm', ['user' => $user->id]) }}" class="text-blue-500 hover:underline">
                                        <i class="fas fa-edit"></i> Assign Roles & Permissions
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
