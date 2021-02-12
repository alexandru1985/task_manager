<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('/public/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/css/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/css/custom.css') }}" rel="stylesheet">

    
    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>
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
window.checkImportCSV = @json(\DB::table('clients')->count());
window.app_url = @json(url(''));
if (window.location.href.indexOf('#_=_') > 0) {
    window.location = window.location.href.replace(/#.*/, '');
}
</script>
</html>
