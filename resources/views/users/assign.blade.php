@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Assign Roles & Permissions(User - {{ $user->name }})</h2>
                {{--@php
//                    $user = Auth::user();

    // Use the helper function to get active roles
    $roles = getActiveRoles($user);
    print_r($roles);
    exit;
                @endphp--}}

                <div class="flex space-x-2">
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                        onclick="window.location.href='{{ route('users.index') }}'"
                    >
                        Back to Users
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
{{--                        <h2 class="text-xl font-bold mb-4">{{ $user->name }}</h2>--}}
                        <div class="flex gap-8">
                            <div class="p-8 bg-sky-100 rounded w-8/12">
                                <ul>
                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                        <div class="form-group" >
                                            <label for="roles">User Type:</label>
                                            <div id="roles">
                                                @php
                                                    $users = getActiveRoles($user);
                                                @endphp
                                                @foreach($users as $role => $status)
                                                    @if($status == 1)
                                                        <p>{{ ucfirst($role) }}</p>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                    <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group" style="margin-top: 10px">
                                            <label for="roles">Roles</label>
                                            <div id="roles">
                                                @foreach($roles as $role)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}"
                                                               class="form-check-input" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="role-{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-top: 10px">
                                            <label for="permissions">Permissions</label>
                                            <div id="permissions">
                                                @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission-{{ $permission->id }}"
                                                               class="form-check-input" {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Assign</button>
                                    </form>
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
