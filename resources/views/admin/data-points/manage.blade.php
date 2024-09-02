@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
        @include('admin.layouts.partials.header')
        <div
            class="bg-white mx-4 my-8 rounded p-8"
            x-data="accountManager()"
           
        >
        @include('admin.layouts.partials.alerts') <!-- Include the partial view -->
        
            <div x-data="accountManager()" class="container mx-auto p-4">
            <h1>{{ ucfirst(str_replace('-', ' ', $formtype)) }} Management</h1>
                <div class="flex flex-wrap mb-4">
                @foreach ($fields as $field => $type)
              
                  <div class="w-1/2 mt-2 pr-2">
                        <label for="{{ $field }}" class="block mb-2">{{ ucfirst($field) }} <span class="text-red-500">*</span></label>
                        @if ($type === 'select' && $field === 'country')
                                <!-- Dropdown for Country -->
                                <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}" class="w-full p-2 border rounded h-10 bg-white">
                                    <option value="" disabled>Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                        @elseif ($type === 'select' && $field === 'symbol')
                                <!-- Dropdown for Symbol -->
                                <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}" class="w-full p-2 border rounded h-10 bg-white">
                                    <option value="" disabled>Select Symbol</option>
                                    <option value="1">$ (Dollar)</option>
                                    <option value="2">€ (Euro)</option>
                                    <option value="3">£ (Pound)</option>
                                    <option value="4">₹ (Rupee)</option>
                                </select>
                        @elseif ($type === 'select')
                            <select id="{{ $field }}" name="{{ $field }}" x-model="{{ $field }}" class="w-full p-2 border rounded h-10 bg-white">
                                <option value="" disabled>Select</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <p
                            x-show="{{ $field.'Error' }}"
                            class="text-red-500 text-sm mt-1"
                            x-text="{{ $field.'Error' }}"
                        ></p>
                        @else
                            <input type="{{ $type }}" name="{{ $field }}" id="{{ $field }}" x-model="{{ $field }}" class="w-full p-2 border rounded h-10">
                            <p
                            x-show="{{ $field.'Error' }}"
                            class="text-red-500 text-sm mt-1"
                            x-text="{{ $field.'Error' }}"
                        ></p>
                            @endif
                    </div>
                  
                @endforeach
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
                        x-model="searchTerm"
                        placeholder="Search Account Code"
                        class="w-full p-2 border rounded"
                    />
                </div>

                <table class="w-full border-collapse border">
                    <thead>
                        <tr>
                            <th class="border p-2">Sr #</th>
                            <template x-for="(field, index) in fields" :key="index">
                              <th class="border p-2" x-text="index"></th>
                          </template>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <tr>
                                <td class="border p-2 text-center" x-text="index + 1"></td>
                                <template x-for="(field, fieldName) in fields" :key="fieldName">
                                <td class="border p-2 text-center" x-text="item[fieldName]"></td>
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
                        @foreach ($fields as $fielderror => $typeerror)
                          {{ $fielderror }}: "",
                          {{ $fielderror.'Error' }}: "",
                      @endforeach
                      fields: @json($fields), // Pass fields to Alpine.js
                        items: @json($data), // Initialize items with the data passed from the controller
                        searchTerm: "",
                        editIndex: null,
                        nameError: "",
                        statusError: "",
                        error: 0, // Declare the error variable here
                        currentUrl: `{{ url()->current() }}`,

                        // Validate fields dynamically
                        validateFields() {
                            
                            this.error = 0; // Reset error count before validation
                                @foreach ($fields as $fieldValide => $typeValide)
                                    if ('{{ $type }}' === 'text') {
                                        if (this.{{ $fieldValide }}.trim() === "") {
                                            this.{{ $fieldValide.'Error' }} = `Please fill this field`;
                                            this.error += 1;
                                        } else {
                                            this.{{ $fieldValide.'Error' }} = "";
                                        }
                                    } else if ('{{ $type }}' === 'select') {
                                        if (this.{{ $fieldValide }} === "") {
                                            this.{{ $fieldValide.'Error' }} = `Please select a Dropdown`;
                                            this.error += 1;
                                        } else {
                                            this.{{ $fieldValide.'Error' }} = "";
                                        }
                                    }
                                @endforeach

                            },

                        submitData() {
                            this.validateFields();
                            console.log(this.error);
                            
                            // this.validateStatus();

                            if (this.error == 0) {
                              const formData = new FormData();
                                @foreach ($fields as $fieldSubmit => $typeSubmit)
                                    formData.append('{{ $fieldSubmit }}', this.{{ $fieldSubmit  }});
                                @endforeach
                                if (this.editIndex !== null) {
                                  formData.append('id', this.items[this.editIndex].id);
                                }
                                console.log(formData);
                                
                                let url = this.currentUrl;

                                ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);

                                this.cancelEdit();
                            }
                        },

                        cancelEdit() {
                            @foreach ($fields as $fieldCancel => $typeCancel)
                                this.{{ $fieldCancel }} = '';
                            @endforeach
                            this.editIndex = null;
                        },

                        editItem(item) {
                          @foreach ($fields as $fieldEdit => $typeEdit)
                              this.{{ $fieldEdit }} = item.{{ $fieldEdit }};
                          @endforeach
                          this.editIndex = this.items.indexOf(item);
                        },

                        get filteredItems() {
                            return this.items.filter(item =>
                                item.name.toString().includes(this.searchTerm)
                            );
                        },

                        handleSuccess(response) {
                            this.items = response.data; // Update the items with the response data
                        }
                    };
                }
            </script>
        </div>
    </div>
@endsection
