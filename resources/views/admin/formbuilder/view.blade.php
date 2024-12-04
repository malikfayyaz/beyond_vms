
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
            // You can now use jQuery UI features here
        };
        document.head.appendChild(script);
    } else {
        console.error("jQuery is not loaded. Please load jQuery first.");
    }
});

</script>
@endsection
