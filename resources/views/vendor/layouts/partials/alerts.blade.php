<!-- resources/views/partials/alerts.blade.php -->
@if (session('message') || session('success'))
    <div id="success-toast" class="fixed bottom-[20px] right-[40px] p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300 z-[9999]" role="alert">
        <span class="font-medium">Success:</span> {{ session('success') }}
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-toast').style.display = 'none';
        }, 5000); // Hide after 5 seconds
    </script>
@endif
@if(session('status'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
        <span class="font-medium">Success:</span> {{ session('status') }}
    </div>
@endif

{{--@if (session('message') || session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
        <span class="font-medium">Success:</span>
        {{ session('success') ?? session('message') }}
    </div>
@endif--}}
@if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
        @foreach ($errors->all() as $error)
            <span class="font-medium">Error:</span> {{ $error }}
        @endforeach
    </div>
@endif
<!-- Display Success Message -->
@if(request()->has('success'))
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        {{ request()->query('success') }}
    </div>
@endif

<!-- Display Error Message -->
@if(request()->has('error'))
    <div class="bg-red-500 text-white p-4 rounded mb-4">
        {{ request()->query('error') }}
    </div>
@endif

<!-- Display Errors -->
<div id="error-messages" class="bg-red-100 text-red-600 p-4 mb-4 rounded error-messages" style="display: none;">
    <!-- Error messages will be injected here by JavaScript -->
</div>

<div id="success-message" class="text-green-500"></div>
