@extends('admin.layouts.app')
@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8" x-data="reasonList()" >


        @include('admin.layouts.partials.alerts')
        <!-- Include the partial view -->

        <div class="mb-4">
            <label for="addValue" class="block text-sm font-medium text-gray-700">Add Value</label>
            <div class="mt-1 flex gap-4 rounded-md">
                <input type="text" name="addValue" id="addValue" x-model="newValue"
                    class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" />
                <button @click="addReason"
                    class="px-4 py-2 bg-blue-500 flex items-center text-white rounded hover:bg-blue-600">
                    <i class="fa-solid fa-plus mr-2"></i>
                    <span>Add</span>
                </button>
            </div>
        </div>

        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        #
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reason List
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(reason, index) in reasons" :key="index">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="reason.id"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="reason.name"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-md">
                            <span @click="openSlideWindow(reason.id,reason.name)"
                                class="text-indigo-600 cursor-pointer hover:text-indigo-900 text-3xl">
                                <i class="fa-solid fa-circle-plus"></i>
                            </span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <!-- Slide Window -->
        <div x-show="slideWindowOpen" class="fixed inset-0 overflow-hidden" aria-labelledby="slide-over-title"
            role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div @click="closeSlideWindow" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                    <div class="w-screen max-w-md">
                        <div @click.stop class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll p-4">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900" id="slide-over-title"
                                        x-text="selectedReason"></h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <span @click="closeSlideWindow"
                                            class="bg-white cursor-pointer hover:text-gray-500 focus:ring-2 focus:ring-indigo-500">
                                            <span class="sr-only">Close panel</span>
                                            <i class="fa-solid fa-circle-xmark text-3xl"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Global Values
                                    </h3>
                                    <div class="mt-2 flex gap-4 rounded-md shadow-sm">
                                        <input type="text" x-model="newGlobalValue"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" />
                                        <input type="hidden" name="category_id" x-model="selectedReasonId"
                                            class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none" />
                                        <span @click="addGlobalValue"
                                            class="px-4 py-2 cursor-pointer bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <i class="fa-solid fa-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900">
                                        Active
                                    </h4>
                                    <div class="mt-2 space-y-2">
                                        <template x-for="(value, key) in activeGlobalValues" :key="key">
                                            <div
                                                class="flex items-center justify-between p-2 bg-gray-100 rounded shadow-sm hover:bg-gray-200 ">
                                                <span x-text="`${key}: ${value}`"></span>
                                                <span @click="deactivateGlobalValue(key)"
                                                    class="text-red-600 hover:text-red-800 cursor-pointer">
                                                    <i class="fa-solid fa-circle-minus"></i>
                                                </span>
                                            </div>
                                        </template>

                                    </div>
                                </div>
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900">
                                        Inactive
                                    </h4>
                                    <div class="mt-2 space-y-2">
                                        <template x-for="(value, key) in inactiveGlobalValues" :key="key">

                                            <div
                                                class="flex items-center justify-between p-2 bg-gray-100 rounded shadow-sm hover:bg-gray-200">
                                                <span x-text="`${key}: ${value}`"></span>
                                                <span @click="activateGlobalValue(key)"
                                                    class="text-blue-600 hover:text-blue-800 cursor-pointer">
                                                    <i class="fa-solid fa-circle-plus"></i>
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
function reasonList() {
    return {
        reasons: @json($setting_category),
        newValue: "",
        slideWindowOpen: false,
        selectedReason: "",
        loading: false,
        selectedReasonId: "",
        newGlobalValue: "",
        activeGlobalValues: {},
        inactiveGlobalValues: {},
        
      
        addReason() {
            if (this.newValue.trim() !== "") {
                // console.log(this.reasons);

                const newReason = {
                    id: Date.now(),
                    name: this.newValue.trim()
                }; // Using timestamp for demo purpose
                this.reasons.push(newReason);

                const formData = new FormData();
                formData.append('name', this.newValue.trim());
                // Call the custom ajax function
                ajaxCall('/admin/setting/info', 'POST', [
                    [onSuccess, ['response']]
                ], formData);
            }
        },
        openSlideWindow(id, name) {
            this.fetchGlobalValues(id);
            setTimeout(() => {
        this.selectedReason = name;
        this.selectedReasonId = id;
        this.slideWindowOpen = true;
    }, 2000); // Delay in milliseconds (e.g., 2000ms = 2 seconds)


        },
        closeSlideWindow() {
            this.slideWindowOpen = false;
        },
        addGlobalValue() {
            if (this.newGlobalValue.trim() !== "") {
                const formData = new FormData();
                formData.append('title', this.newGlobalValue.trim());
                formData.append('category_id', this.selectedReasonId);
                //   this.activeGlobalValues.push(this.newGlobalValue.trim());
                ajaxCall('/admin/setting/store', 'POST', [
                    [onSuccess, ['response']]
                ], formData);
                this.newGlobalValue = "";
            }
        },
        deactivateGlobalValue(index, settingId) {
            const value = this.activeGlobalValues.splice(index, 1)[0];
            this.inactiveGlobalValues.push(value);
            // Call backend to update the status to inactive
            this.updateSettingStatus(settingId, 'inactive');
        },
        activateGlobalValue(index, settingId) {
            const value = this.inactiveGlobalValues.splice(index, 1)[0];
            this.activeGlobalValues.push(value);
            // Call backend to update the status to active
            this.updateSettingStatus(settingId, 'active');
        },
        fetchGlobalValues(categoryId) {
            const url = `/admin/setting/fetch/${categoryId}`;

            // Fetch the active and inactive settings from the backend
            ajaxCall(url, 'GET', [
                [this.populateGlobalValues, ['response']]
            ]);
            //  console.log(this.activeGlobalValues);

        },
        populateGlobalValues(response) {
            console.log(response);
            
              // Convert response.active to an object with key-value pairs
                this.activeGlobalValues = Object.fromEntries(
                    Object.entries(response.active).map(([id, value]) => [id, value])
                );

                // Convert response.inactive to an object with key-value pairs
                this.inactiveGlobalValues = Object.fromEntries(
                    Object.entries(response.inactive).map(([id, value]) => [id, value])
                );
                this.activeGlobalValues =this.activeGlobalValues;
                this.inactiveGlobalValues =this.inactiveGlobalValues;
            // this.loading = false; // Set loading to false after data is fetched
            console.log('Data loaded:', this.activeGlobalValues, this.inactiveGlobalValues);
        },

        updateSettingStatus(settingId, status) {
            const url = `/admin/setting/update-status/${settingId}`;
            const formData = new FormData();
            formData.append('status', status);

            // Call backend to update the setting's status
            ajaxCall(url, 'POST', [
                [onSuccess, ['response']]
            ], formData);
        },
    };
}
</script>