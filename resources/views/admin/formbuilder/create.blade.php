@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16"  x-data="extraData({
        formType: '{{ $editMode ? $formBuilder->type ?? '' : '' }}',
        formStatus: '{{ $editMode ? $formBuilder->status ?? '' : '' }}',
    })">
        <div class="flex space-x-4 mx-4 my-4">
            <div class="flex-1">
                <label for="formType" class="block mb-2"
                >Form Type:
                <span class="text-red-500">*</span></label
                >
                <select
                    id="formType"
                    x-model="formType" 
                    class="w-full px-3 py-2 border rounded-md"
                    
                >
                    <option value="" disabled selected>Select</option>
                    @foreach (formType() as $key => $value)
                        <option value="{{ $key }}" 
                                @if(in_array($key, $existingTypes)) 
                                    disabled 
                                @endif
                        >
                            {{ $value }} 
                            @if(in_array($key, $existingTypes)) 
                                (Already Exists)
                            @endif
                        </option>
                 @endforeach
                </select>
                <p class="text-red-500 text-sm mt-1" x-text="formTypeError"></p>
            </div>

            <div class="flex-1">
                <label for="formStatus" class="block mb-2"
                >Status:<span class="text-red-500">*</span></label
                >
                <select id="formStatus" x-model="formStatus" class="w-full p-2 border rounded h-10">
                    <option value="" selected>Select</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <p
                class="text-red-500 text-sm mt-1"
                x-text="formStatusError"
                ></p>
            </div>
            
        </div>
        <div>
            <div id="fb-editor" class="mx-4 my-4 mb-1"></div>
            <p class="text-red-500 text-sm mb-4 mx-4" x-text="formMainError"></p>
        </div>
    </div>

<script>
    function extraData(initialData = { formType: '', formStatus: '' }) {
        return {
            formTypeError: '',
            formStatusError: '',
            formMainError: '',
            formType: initialData.formType || '', // Use initial value or default to empty
            formStatus: initialData.formStatus || '', // Use initial value or default to empty
            // Other Alpine.js properties or methods
        };
    }
    document.addEventListener('DOMContentLoaded', function () {
        if (window.$) {
            // Dynamically load jQuery UI
            let script = document.createElement('script');
            script.src = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js";
            script.type = "text/javascript";
            script.onload = function () {
                console.log("jQuery UI loaded successfully!");
            };
            document.head.appendChild(script);

            // Initialize form builder
            jQuery(function ($) {
                var fbTemplate = document.getElementById("fb-editor");

                // Get the saved data if in edit mode
                let savedData = @json($formBuilder->data ?? '[]'); // Default to empty array if no data

                // Set form builder options
                var options = {
                    formData: savedData, // Load saved form data into the editor
                    disableFields: ['button'],
                    
                    onSave: function (evt, formData) {

                        const formType = document.getElementById('formType').value;
                        const formStatus = document.getElementById('formStatus').value;
                       
                        let errors = false;
                        const formTypeError = document.querySelector('[x-text="formTypeError"]');
                        const formStatusError = document.querySelector('[x-text="formStatusError"]');
                        const formMainError = document.querySelector('[x-text="formMainError"]');
                        formTypeError.textContent = '';
                        formStatusError.textContent = '';
                        formMainError.textContent = '';

                        if (!formType) {
                            formTypeError.textContent = 'Form Type is required.';
                            errors = true;
                        }
                        if (!formStatus) {
                            formStatusError.textContent = 'Form Status is required.';
                            errors = true;
                        }

                        try {
                            const parsedData = JSON.parse(formData); // Attempt to parse the JSON data
                            if (!Array.isArray(parsedData) || parsedData.length === 0) {
                                formMainError.textContent = 'Form Builder data is required.';
                                errors = true;
                            }
                        } catch (e) {
                            formMainError.textContent = 'Invalid Form Builder data.';
                            errors = true;
                        }


                        if (errors) {
                            return; // Stop if validation fails
                        }
                        // Determine URL and HTTP method based on edit mode
                        let url = '{{ $editMode ? route("admin.formbuilder.update", $editIndex) : route("admin.formbuilder.save") }}';
                        let method = 'POST';

                        // Prepare FormData object
                        let formDataObj = new FormData();
                        formDataObj.append('type', formType);
                        formDataObj.append('data', formData); // Pass JSON string
                        formDataObj.append('status', formStatus);
                        if (method === 'PUT') {
                            formDataObj.append('_method', 'PUT'); // Include method override for Laravel
                        }
                        // $(".render-wrap").formRender({ formDataObj });

                        // Perform AJAX call
                        ajaxCall(url, method, [
                            [onSuccess, ['response']]
                        ], formDataObj);
                    }
                };

                // Initialize form builder with options
                $(fbTemplate).formBuilder(options);
            });
        } else {
            console.error("jQuery is not loaded. Please load jQuery first.");
        }
    });
</script>


    
@endsection
