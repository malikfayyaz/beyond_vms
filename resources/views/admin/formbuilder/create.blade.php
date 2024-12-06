@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        <div class="flex space-x-4 mx-4 my-4">
            <div class="flex-1">
                <label for="formType" class="block mb-2"
                >Form Type:
                <span class="text-red-500">*</span></label
                >
                <select
                    id="formType"
                    x-model="formData.formType"
                    class="w-full px-3 py-2 border rounded-md"
                    :class="{'border-red-500': errors.formType}"
                >
                    <option value="" disabled selected>Select</option>
                    @foreach (formType() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <p
                class="text-red-500 text-sm mt-1"
                x-text="errors.formType"
                ></p>
            </div>
            <div class="flex-1">
                <label for="formStatus" class="block mb-2"
                >Status:</label
                >
                <select id="formStatus" x-model="formData.formStatus" class="w-full p-2 border rounded h-10">
                    <option value="" disabled selected>Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <p
                class="text-red-500 text-sm mt-1"
                x-text="errors.formStatus"
                ></p>
            </div>
        </div>
        <div id="fb-editor" class="mx-4 my-4"></div>
    </div>

    <script>
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

                    var options = {
                        formData: savedData, // Load saved form data into the editor
                        onSave: function (evt, formData) {
                            // Determine URL and HTTP method based on edit mode
                            let url = '{{ $editMode ? route("admin.formbuilder.update", $editIndex) : route("admin.formbuilder.save") }}';
                            let method = 'POST';

                            // Prepare FormData object
                            let formDataObj = new FormData();
                            formDataObj.append('type', 1);
                            formDataObj.append('data', formData); // Pass JSON string
                            formDataObj.append('status', 'active');
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
