
@extends('admin.layouts.app')
@section('content')
<div>
    <div id="fb-editor"></div>
    <button @click="submitForm">Save Form</button>
</div>
<div id="build-wrap"></div>
<script>
    
          

      
    document.addEventListener('DOMContentLoaded', function() {
        if (window.$) {
            const fbEditor = $('#fb-editor');
            $(fbEditor).formBuilder();
            
    }
});
</script>
@endsection
