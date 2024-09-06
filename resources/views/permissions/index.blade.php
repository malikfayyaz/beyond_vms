@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
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
                <!-- Tab List -->
                <ul
                    x-ref="tablist"
                    @keydown.right.prevent.stop="$focus.wrap().next()"
                    @keydown.home.prevent.stop="$focus.first()"
                    @keydown.page-up.prevent.stop="$focus.first()"
                    @keydown.left.prevent.stop="$focus.wrap().prev()"
                    @keydown.end.prevent.stop="$focus.last()"
                    @keydown.page-down.prevent.stop="$focus.last()"
                    role="tablist"
                    class="-mb-px flex items-center text-gray-500 bg-gray-100 py-1 px-1 rounded-t-lg gap-4"
                >
                    <!-- Tab -->
                </ul>

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
                        <div class="flex gap-8">
                            <div class="p-8 bg-sky-100 rounded w-8/12">
                                <ul>
                                    @foreach ($permissions as $permission)
                                        <label for="name" class="block text-gray-700 font-bold mr-2">
                                        <li>{{ $permission->name }} - <a href="{{ route('permissions.edit', $permission->id) }}"><i class="fas fa-edit"></i></a></li>
                                        </label>
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
