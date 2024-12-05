@extends('admin.layouts.app')
@section('content')

<div id="fb-editor"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.$) {
        // Dynamically create a script element for jQuery UI
        let script = document.createElement('script');
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js";
        script.type = "text/javascript";
        script.onload = function () {
            console.log("jQuery UI loaded successfully!");
        };
        document.head.appendChild(script);
        jQuery(function ($) {
            var fbTemplate = document.getElementById("fb-editor");
            var options = {
                onSave: function (evt, formData) {
                    console.log(formData);
                    
                console.log("formbuilder saved");
                //   toggleEdit(false);
                // $(".render-wrap").formRender({ formData });
                }
            };
            $(fbTemplate).formBuilder(options);
            });
    } else {
        console.error("jQuery is not loaded. Please load jQuery first.");
    }
});

</script>

@endsection
