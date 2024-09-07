@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8" x-data="accountManager()">
            @include('admin.layouts.partials.alerts') <!-- Include the partial view -->

            <div class="container mx-auto p-4">
                <h1>Job Family Group Management</h1>
                <div class="flex flex-wrap mb-4">
                    <!-- Job Family Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="job_family" class="block mb-2">Job Family <span class="text-red-500">*</span></label>
                        <select
                            id="job_family"
                            name="job_family"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="job_family"
                        >
                            <option value="" disabled>Select Job Family</option>
                        @foreach ($jobfamily as $family)
                            <option value="{{ $family->id }}">{{ $family->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="job_familyError"></p>
                    </div>

                    <!-- Job Family Group Field -->
                    <div class="w-1/2 mt-2 pr-2">
                        <label for="job_family_group" class="block mb-2">Job Family Group <span class="text-red-500">*</span></label>
                        <select
                            id="job_family_group"
                            name="job_family_group"
                            class="w-full p-2 border rounded h-10 bg-white"
                            x-model="job_family_group"
                        >
                            <option value="" disabled>Select Job Family Group</option>
                        @foreach ($jobfamilygrp as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1" x-text="job_family_groupError"></p>
                    </div>
                </div>

                <div class="flex mb-4">
                    <button
                        @click="submitData()"
                        class="bg-blue-500 text-white px-4 py-2 rounded mr-2"
                    >
                        <span x-text="editIndex !== null ? 'Update' : 'Add'"></span>
                    </button>
                    <button
                        @click="cancelEdit()"
                        class="bg-gray-500 text-white px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                </div>

                <div class="mb-4">
                    <input
                        type="text"
                        placeholder="Search Job Family"
                        class="w-full p-2 border rounded"
                        x-model="searchTerm"
                    />
                </div>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Sr #</th>
                            <th class="border p-2">Job Family</th>
                            <th class="border p-2">Job Family Group</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <tr>
                                <td class="border p-2 text-center" x-text="index + 1"></td>
                                <td class="border p-2 text-center" x-text="item.job_family.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center" x-text="item.job_family_group.name ?? 'N/A'"></td>
                                <td class="border p-2 text-center">
                                    <span @click="editItem(item)" class="text-gray-600 cursor-pointer">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function accountManager() {
        return {
            job_family: "",
            job_family_group: "",
            job_familyError: "",
            job_family_groupError: "",
            items: @json($data),
            searchTerm: "",
            editIndex: null,
            error: 0,
            currentUrl: `{{ url()->current() }}`,

            validateFields() {
                this.error = 0; 
                if (this.job_family.trim() === "") {
                    this.job_familyError = `Please select a Job Family`;
                    this.error += 1;
                } else {
                    this.job_familyError = "";
                }
                if (this.job_family_group.trim() === "") {
                    this.job_family_groupError = `Please select a Job Family Group`;
                    this.error += 1;
                } else {
                    this.job_family_groupError = "";
                }
            },

            submitData() {
                this.validateFields();
                if (this.error === 0) {
                    const formData = new FormData();
                    formData.append('job_family', this.job_family);
                    formData.append('job_family_group', this.job_family_group);
                    if (this.editIndex !== null) {
                        formData.append('id', this.items[this.editIndex].id);
                    }

                    fetch(this.currentUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.text()) // Change to response.text() to handle non-JSON responses
                    .then(text => {
                        try {
                            const data = JSON.parse(text); // Attempt to parse JSON
                            if (data.success) {
                                window.location.href = data.redirect_url;
                            } else {
                                console.error('Validation errors:', data.errors);
                                this.job_familyError = data.errors.job_family || '';
                                this.job_family_groupError = data.errors.job_family_group || '';
                            }
                        } catch (e) {
                            console.error('Response is not valid JSON:', text);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            },

            cancelEdit() {
                this.job_family = '';
                this.job_family_group = '';
                this.editIndex = null;
            },

            editItem(item) {
                this.job_family = item.job_family;
                this.job_family_group = item.job_family_group;
                this.editIndex = this.items.findIndex(i => i.id === item.id);
            },

            get filteredItems() {
                return this.items.filter(item =>
                    item.job_family.toString().includes(this.searchTerm)
                    );
                }
            };
        }
    </script>

@endsection
