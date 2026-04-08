<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    {{-- @include('partials.gtm-head') --}}
    <title>@yield('title') | {{ settings()->application_name ?? 'Isotope IT' }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ settings()->application_slogan ?? 'Isotope IT' }}" />
    <meta name="keywords" content="{{ settings()->application_name ?? 'Isotope IT' }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ settings()->company_name ?? 'Isotope IT' }}" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:site_name" content="{{ settings()->application_name ?? 'Isotope IT' }}" />
    <link rel="canonical" href="{{ config('app.url') }}" />
    <link rel="shortcut icon" href="{{ isset(settings()->favicon) ? Storage::url(settings()->favicon) : asset('isotope/metronic/img/favicon.ico') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />


    <link href="{{ asset('isotope/metronic/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('isotope/metronic/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />


    @stack('css')
</head>

<body>
    {{-- @include('partials.gtm-body') --}}
    @yield('content')
    <script src="{{ asset('isotope/metronic/js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('isotope/metronic/js/scripts.bundle.min.js') }}"></script>
    <script src="{{ asset('isotope/metronic/libs/alpine.min.js') }}"></script>
    <script src="{{ asset('isotope/metronic/js/isotopeit.js') }}"></script>
    @stack('js')


</body>

</html>