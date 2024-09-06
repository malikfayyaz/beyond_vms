@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Added Roles</h2>
                <div class="flex space-x-2">
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                        onclick="window.location.href='{{ route('roles.create') }}'"
                    >
                        Create New Role
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                        onclick="window.location.href='{{ route('admin.dashboard') }}'"
                    >
                        Back To Dashboard
                    </button>
                </div>
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
                <!-- Panels -->
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
{{--                        <h2 class="text-xl font-bold mb-4">Description</h2>--}}
                        <div class="flex gap-8">
                            <div class="p-8 bg-sky-100 rounded w-8/12">
                                <ul>
                                    @foreach ($roles as $role)
                                        <label for="name" class="block text-gray-700 font-bold mr-2">
                                            <li>{{ $role->name }}
                                        </label>
                                        - <a href="{{ route('roles.edit', $role->id) }}"><i class="fas fa-edit"></i></a></li>
                                    @endforeach
                                </ul>

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
