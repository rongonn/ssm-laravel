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
	@if (isset(settings()->custom_css))
		<style>
			{!! settings()->custom_css !!}
		</style>
	@endif

	@stack('css')
</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed">
    {{-- @include('partials.gtm-body') --}}
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			@include('isotope::elements.sidebar')
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				@include('isotope::elements.header')
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					@include('isotope::elements.breadcrumb')
					<div class="post d-flex flex-column-fluid" id="kt_post">
						<div id="kt_content_container" class="container-fluid">
							@yield('content')
						</div>
					</div>
				</div>
				@include('isotope::elements.footer')
			</div>
		</div>
	</div>


	<script src="{{ asset('isotope/metronic/js/plugins.bundle.js') }}"></script>
	<script src="{{ asset('isotope/metronic/js/scripts.bundle.min.js') }}"></script>
    <script src="{{ asset('isotope/metronic/libs/alpine.min.js') }}"></script>
	<script src="{{ asset('isotope/metronic/js/isotopeit.js') }}"></script>
	@include('isotope::elements.toastr')
	@stack('js')
</body>

</html>