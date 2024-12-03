<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([ 'resources/sass/app.scss'])
    @vite([ 'resources/css/app.css'])
    <title>Tailwind + Alpine + Vite</title>
  </head>
  <body class="bg-gray-100">
    <div
      x-data="{
      miniSidebar: true,
      currentTheme: localStorage.getItem('theme') || 'theme-1',
      darkMode: localStorage.getItem('darkMode') === 'true',
      setTheme(theme) {
          this.currentTheme = theme;
          localStorage.setItem('theme', theme);
      },
      toggleDarkMode() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('darkMode', this.darkMode);
      }
    }"
      :class="[currentTheme, {'dark-mode': darkMode}]"
    >
    @yield('content')

    </div>
    @vite([ 'resources/js/app.js','resources/js/ajax-functions.js'])
  </body>
</html>
