@extends('admin.layouts.app')
@section('content')
@include('admin.layouts.partials.dashboard_side_bar')
    <div class="ml-16">
            <div id="fb-editor" class="mx-4 my-4"></div>
    </div>
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
                    // console.log(formData);
                    
                    let url = '{{ route("admin.form-builder.save") }}';
                    let method = 'POST';
                    
                    let formDataObj = new FormData();
                    formDataObj.append('type', 1);
                    formDataObj.append('data', formData); // Pass JSON string
                    formDataObj.append('status', 'active');

                    //   toggleEdit(false);
                    // $(".render-wrap").formRender({ formData });
                    ajaxCall(url, method,  [[onSuccess, ['response']]], formDataObj);
               
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
