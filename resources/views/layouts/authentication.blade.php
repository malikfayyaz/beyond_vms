<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite([ 'resources/css/app.css'])
  </head>
  <body class="h-full bg-gray-100">
  <div class="h-screen flex items-center justify-center">
   

    @yield('content')
    </div>
    @vite([ 'resources/js/app.js','resources/js/ajax-functions.js'])
  </body>
</html>
