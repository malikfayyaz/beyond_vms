@extends('admin.layouts.app')
@section('content')

<div id="fb-editor"></div>
<button id="save-form" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Save Form</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.$) {
        // Dynamically create a script element for jQuery UI
        let script = document.createElement('script');
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js";
        script.type = "text/javascript";
        script.onload = function () {
            console.log("jQuery UI loaded successfully!");

            // Ensure FormBuilder is initialized only once
            if (!window.fbEditor) {
                $('#fb-editor').empty();
                window.fbEditor = $('#fb-editor').formBuilder();  // Initialize the FormBuilder only once
            }

            // Save form data on button click
            document.getElementById('save-form').addEventListener('click', function () {
                const formData = window.fbEditor.actions.getData('json'); // Get form data in JSON format

                fetch('{{ route('admin.form-builder.save') }}', {
                    method: 'POST',
                    
                    body: JSON.stringify({
                        type: 1, // Set your desired type (example: 1 for general forms)
                        data: formData, // Pass the form data
                        status: 'active', // Set form status
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Form saved successfully!');
                    } else {
                        alert('Failed to save the form.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        };
        document.head.appendChild(script);
    } else {
        console.error("jQuery is not loaded. Please load jQuery first.");
    }
});
</script>

@endsection
