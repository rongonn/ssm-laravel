<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ function_exists('settings') && isset(settings()->application_name) ? settings()->application_name : 'Style Studio Mart' }} – Premium Salon & Wellness Experience</title>
        
        @php
            $defaultSlogan = 'Premium salon and wellness experience dedicated to redefining beauty standards through artisanal techniques and luxury care.';
            $defaultKeywords = 'salon, beauty, spa, hair care, styling, wellness, aesthetic center, Style Studio Mart, beauty parlor';
            
            $appName = function_exists('settings') && isset(settings()->application_name) ? settings()->application_name : 'Style Studio Mart';
            $appSlogan = function_exists('settings') && isset(settings()->application_slogan) ? settings()->application_slogan : $defaultSlogan;
            $appKeywords = function_exists('settings') && isset(settings()->meta_keywords) ? settings()->meta_keywords : $defaultKeywords;
            
            $bannerImage = function_exists('settings') && isset(settings()->landing_banner) ? asset('storage/' . settings()->landing_banner) : asset('favicon.ico');
        @endphp

        <meta name="description" content="{{ $appSlogan }}">
        <meta name="keywords" content="{{ $appKeywords }}">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $appName }} – Premium Salon & Wellness Experience">
        <meta property="og:description" content="{{ $appSlogan }}">
        <meta property="og:image" content="{{ $bannerImage }}">
        <meta property="og:site_name" content="{{ $appName }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ $appName }} – Premium Salon & Wellness Experience">
        <meta property="twitter:description" content="{{ $appSlogan }}">
        <meta property="twitter:image" content="{{ $bannerImage }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ function_exists('settings') && isset(settings()->favicon) ? asset('storage/' . settings()->favicon) : asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ function_exists('settings') && isset(settings()->favicon) ? asset('storage/' . settings()->favicon) : asset('favicon.ico') }}">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
