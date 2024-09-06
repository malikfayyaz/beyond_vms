@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Create New Roles</h2>
                @php
                        $user = Auth::user();
                        //$user_types = getActiveRoles($user);
                        $user_types = userType();
                @endphp
                <button
                    type="button"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                    onclick="window.location.href='{{ route('roles.index') }}'"
                >
                    Back to Roles
                </button>
            </div>
            <!-- Tabs -->
            <div
                x-data="{
    selectedId: null,
    init() {
        // Set the first available tab on the page on page load.
        this.$nextTick(() => this.select(this.$id('tab', 1)))
    },
    select(id) {
        this.selectedId = id
    },
    isSelected(id) {
        return this.selectedId === id
    },
    whichChild(el, parent) {
        return Array.from(parent.children).indexOf(el) + 1
    }
}"
                x-id="['tab']"
                class="w-full"
            >
                <div
                    role="tabpanels"
                    class="rounded-b-md border border-gray-200 bg-white"
                >
                    <!-- First Tab -->
                    <section
                        x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                        x-data='{
                }'

                        :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                        role="tabpanel"
                        class="p-8"
                    >
                        <div class="flex gap-8">
                            <div class="p-8 bg-sky-100 rounded w-8/12">
                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf
                                    <!-- Role Name Field -->
                                    <div class="flex space-x-4 mt-4">
                                        <div class="flex-1">
                                            <label for="user_type" class="block mb-2">User Type</label>
                                            <div class="relative">
                                                <select
                                                    id="user_type"
                                                    name="user_type"
                                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                                >
                                                    @foreach($user_types as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-4 mt-4">
                                        <div class="flex-1">
                                            <label for="name" class="block mb-2">Role Name</label>
                                            <div class="relative">
                                                <input
                                                    type="text"
                                                    id="name"
                                                    name="name"
                                                    required
                                                    class="w-full h-12 px-4 bg-white text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="mt-4">
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none"
                                        >
                                            Create Role
                                        </button>
                                    </div>
                                </form>

                    <span
                        class="font-semibold"
                    ></span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
