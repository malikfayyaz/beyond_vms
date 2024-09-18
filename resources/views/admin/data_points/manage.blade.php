@extends('admin.layouts.app')

@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    <div class="bg-white mx-4 my-8 rounded p-8" x-data="accountManager()">
        @include('admin.layouts.partials.alerts')
        <!-- Include the partial view -->

        <div x-data="accountManager()" class="container mx-auto p-4">
            <h1>{{ ucfirst(str_replace('-', ' ', $formtype)) }} Management</h1>
            <div class="flex flex-wrap mb-4">
                <!-- Blade template -->
                @foreach ($fields as $field => $attributes)
                <div class="w-1/2 mt-2 pr-2">
                    <label for="{{ $field }}" class="block mb-2">
                        {{ ucfirst($attributes['label']) }} <span class="text-red-500">*</span>
                    </label>

                    @if ($attributes['type'] === 'select' && $field === 'country')
                    <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}"
                        class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select {{ ucfirst($attributes['label']) }}</option>
                        @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @elseif ($attributes['type'] === 'select' && $field === 'symbol')
                    <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}"
                        class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select {{ ucfirst($attributes['label']) }}</option>
                        @foreach (currency() as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @elseif ($attributes['type'] === 'select')
                    <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}"
                        class="w-full p-2 border rounded h-10 bg-white">
                        <option value="" disabled>Select {{ ucfirst($attributes['label']) }}</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @else
                    <input type="{{ $attributes['type'] }}" name="{{ $field }}" id="{{ $field }}" x-model="{{ $field }}"
                        class="w-full p-2 border rounded h-10">
                    @endif

                    <p x-show="{{ $field.'Error' }}" class="text-red-500 text-sm mt-1" x-text="{{ $field.'Error' }}">
                    </p>
                </div>
                @endforeach

            </div>

            <div class="flex mb-4">
                <button @click="submitData()" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">
                    <span x-text="editIndex !== null ? 'Update' : 'Add'"></span>
                </button>
                <button @click="cancelEdit()" class="bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            </div>

            <div class="mb-4">
                <input type="text" x-model="searchTerm" placeholder="Search Account Code"
                    class="w-full p-2 border rounded" />
            </div>

            <table class="w-full border-collapse border">
                <thead>
                    <tr>
                        <th class="border p-2">Sr #</th>
                        <template x-for="(field, index) in fields" :key="index">
                            <th class="border p-2" x-text="fields[index]['label']"></th>
                        </template>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr>
                            <td class="border p-2 text-center" x-text="index + 1"></td>
                            <template x-for="(field, fieldName) in fields" :key="fieldName">
                                <td class="border p-2 text-center">
                                    <template x-if="fieldName === 'country'">
                                        <span x-text="item.country.name"></span>
                                    </template>
                                    <template x-if="fieldName === 'symbol'">
                                        <span x-text="item.setting.title"></span>
                                    </template>
                                    <template x-if="fieldName !== 'country' && fieldName !== 'symbol'">
                                        <span x-text="item[fieldName]"></span>
                                    </template>
                                </td>
                            </template>
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

        <script>
        function accountManager() {
            return {
                fields: @json($fields), // Pass fields to Alpine.js
                items: @json($data), // Initialize items with the data passed from the controller
                searchTerm: "",
                editIndex: null,
                error: 0,
                currentUrl: `{{ url()->current() }}`,

                // Dynamically create properties for each field and fieldError
                init() {
                    @foreach($fields as $fielderror => $typeerror)
                    this['{{ $fielderror }}'] = ""; // Initialize field value
                    this['{{ $fielderror }}Error'] = ""; // Initialize error message
                    @endforeach
                },

                // Validate fields dynamically
                validateFields() {
                    this.error = 0; // Reset error count before validation

                    @foreach($fields as $fieldValide => $type)
                    if ('{{ $type["type"] }}' === 'text' || '{{ $type["type"] }}' === 'number') {
                        if (this['{{ $fieldValide }}'].trim() === "") {
                            this['{{ $fieldValide }}Error'] = `Please fill this field`;
                            this.error += 1;
                        } else {
                            this['{{ $fieldValide }}Error'] = "";
                        }
                    } else if ('{{ $type["type"] }}' === 'select') {
                        if (this['{{ $fieldValide }}'] === "") {
                            this['{{ $fieldValide }}Error'] = `Please select a Dropdown`;
                            this.error += 1;
                        } else {
                            this['{{ $fieldValide }}Error'] = "";
                        }
                    }
                    @endforeach
                },

                submitData() {
                    this.validateFields();
                    console.log(this.error);

                    if (this.error === 0) {
                        const formData = new FormData();
                        @foreach($fields as $fieldSubmit => $typeSubmit)
                        formData.append('{{ $fieldSubmit }}', this['{{ $fieldSubmit }}']);
                        @endforeach

                        if (this.editIndex !== null) {
                            formData.append('id', this.items[this.editIndex].id);
                        }

                        console.log(formData);

                        let url = this.currentUrl;
                        ajaxCall(url, 'POST', [
                            [onSuccess, ['response']]
                        ], formData);

                        this.cancelEdit();
                    }
                },

                cancelEdit() {
                    @foreach($fields as $fieldCancel => $typeCancel)
                    this['{{ $fieldCancel }}'] = ''; // Reset field values
                    @endforeach
                    this.editIndex = null;
                },

                editItem(item) {
                    @foreach($fields as $fieldEdit => $typeEdit)
                    @if($fieldEdit === 'country')
                    this['{{ $fieldEdit }}'] = item.country_id;
                    @elseif($fieldEdit === 'symbol')
                    this['{{ $fieldEdit }}'] = item.symbol_id;
                    @else
                    this['{{ $fieldEdit }}'] = item['{{ $fieldEdit }}'];
                    @endif
                    @endforeach
                    this.editIndex = this.items.indexOf(item);
                },

                get filteredItems() {
                    return this.items.filter(item => item.name.toString().includes(this.searchTerm));
                },

                handleSuccess(response) {
                    this.items = response.data; // Update items with response data
                }
            };
        }
        </script>
    </div>
</div>
@endsection