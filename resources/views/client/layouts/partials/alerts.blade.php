<!-- resources/views/partials/alerts.blade.php -->
<div class="flex justify-center mb-8">
{{--    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="h-12" />--}}
</div>
@if(session('status'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
                <span class="font-medium">Success:</span> {{ session('status') }}
            </div>
        @endif
@if (session('message') || session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
        <span class="font-medium">Success:</span>
        {{ session('success') ?? session('message') }}
    </div>
@endif
@if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
        @foreach ($errors->all() as $error)
            <span class="font-medium">Error:</span> {{ $error }}
        @endforeach
    </div>
@endif
