<div class="footer py-4 d-flex flex-lg-column bg-white w-100 shadow-lg" id="kt_footer">
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <div class="text-dark order-2 order-md-1">
            <span class="text-dark fw-semibold me-1">&copy; {{ date('Y') > 2023 ? '2023 - '.date('Y') : 2023 }}</span>
            <a href="https://isotopeit.com" target="_blank" class="text-isotope text-hover-primary">{!! isset(settings()->footer_text) ? settings()->footer_text : 'Isotope IT Ltd.' !!}</a>
        </div>
    </div>
</div>