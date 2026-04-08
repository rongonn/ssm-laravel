@extends('isotope::master')

@section('title', 'Settings')

@push('buttons')
    <button type="submit" form="setting-form" class="btn btn-sm btn-isotope fw-bold">
        <i class="fas fa-paper-plane"></i> Save All Settings
    </button>
@endpush

@section('content')

    <div class="card shadow-sm border-0 mb-8" style="border-radius: 16px;">
        <div class="card-header pt-7 pb-0 border-0">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">System Settings</span>
                <span class="text-muted mt-1 fw-bold fs-7">Manage your application configuration and assets</span>
            </h3>
            <div class="card-toolbar">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder" role="tablist">
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#tab_general">
                            <i class="fas fa-info-circle me-2"></i> General Info
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_branding">
                            <i class="fas fa-paint-brush me-2"></i> Logos & Branding
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_banners">
                            <i class="fas fa-images me-2"></i> Page Banners
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#tab_slider">
                            <i class="fas fa-layer-group me-2"></i> Landing Slider
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card-body py-8">
            <div class="tab-content">
                {{-- TAB: GENERAL INFO --}}
                <div class="tab-pane fade show active" id="tab_general" role="tabpanel">
                    <form action="{{ route(tenant() ? 'settings.store' : 'owner.settings.store') }}" method="POST" enctype="multipart/form-data" id="setting-form">
                        @csrf
                        <div class="row g-8">
                            <div class="col-xl-6">
                                <div class="card bg-light-primary border-0 rounded-4">
                                    <div class="card-body">
                                        <h4 class="text-primary fw-bolder mb-5"><i class="fas fa-cog text-primary me-2"></i> Application Identity</h4>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold required">Application Name</label>
                                            <input type="text" name="text[application_name]" class="form-control form-control-solid" value="{{ $settings->application_name ?? 'Isotope' }}" required>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold required">Application Slogan</label>
                                            <input type="text" name="text[application_slogan]" class="form-control form-control-solid" value="{{ $settings->application_slogan ?? 'Software Solution With Smart Technologies' }}" required>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold required">Currency Symbol/Code</label>
                                            <input type="text" name="text[currency]" class="form-control form-control-solid" value="{{ $settings->currency ?? 'tk' }}" required>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold">Meta Keywords (SEO)</label>
                                            <input type="text" name="text[meta_keywords]" class="form-control form-control-solid" value="{{ $settings->meta_keywords ?? '' }}">
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold">Footer Text</label>
                                            <textarea rows="2" name="text[footer_text]" class="form-control form-control-solid">{!! $settings->footer_text ?? 'Developed by Isotope IT LTD.' !!}</textarea>
                                        </div>
                                        <div>
                                            <label class="form-label fw-bold">Custom CSS</label>
                                            <textarea rows="4" name="text[custom_css]" class="form-control form-control-solid font-monospace fs-8">{!! $settings->custom_css ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6">
                                <div class="card bg-light-success border-0 rounded-4 mb-8">
                                    <div class="card-body">
                                        <h4 class="text-success fw-bolder mb-5"><i class="fas fa-building text-success me-2"></i> Company Details</h4>
                                        <div class="mb-5">
                                            <label class="form-label fw-bold required">Company Name</label>
                                            <input type="text" class="form-control form-control-solid" name="text[company_name]" value="{{ $settings->company_name ?? 'Isotope IT LTD.' }}" required>
                                        </div>
                                        <div class="row g-5 mb-5">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold required">Email</label>
                                                <input type="email" class="form-control form-control-solid" name="text[company_email]" value="{{ $settings->company_email ?? 'info@isotopeit.com' }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold required">Phone</label>
                                                <input type="text" class="form-control form-control-solid" name="text[company_phone]" value="{{ $settings->company_phone ?? '01700000000' }}" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label fw-bold">Address</label>
                                            <input type="text" class="form-control form-control-solid" name="text[company_address]" value="{{ $settings->company_address ?? 'Dhaka Bangladesh' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card bg-light-info border-0 rounded-4 mb-8">
                                    <div class="card-body">
                                        <h4 class="text-info fw-bolder mb-5"><i class="fas fa-share-alt text-info me-2"></i> Social Links</h4>
                                        <div class="row g-5">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Facebook</label>
                                                <input type="url" name="text[facebook_url]" class="form-control form-control-solid" value="{{ $settings->facebook_url ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Instagram</label>
                                                <input type="url" name="text[instagram_url]" class="form-control form-control-solid" value="{{ $settings->instagram_url ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">YouTube</label>
                                                <input type="url" name="text[youtube_url]" class="form-control form-control-solid" value="{{ $settings->youtube_url ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Telegram Chat IDs</label>
                                                <input type="text" name="text[telegram_chat_ids]" class="form-control form-control-solid" value="{{ $settings->telegram_chat_ids ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Form stays open for other tabs -->
                </div>

                {{-- TAB: BRANDING --}}
                <div class="tab-pane fade" id="tab_branding" role="tabpanel">
                    <div class="row g-8">
                        <div class="col-md-4">
                            <div class="card bg-light border-0 rounded-4 text-center">
                                <div class="card-body">
                                    <h5 class="fw-bolder mb-4">Main Logo</h5>
                                    <div class="image-input-container">
                                        <label class="image-uploader shadow-sm" style="height: 150px; background-image: url('{{ isset($settings->company_logo) ? asset('storage/' . $settings->company_logo) : asset('isotope/metronic/img/isotopeit.png') }}')">
                                            <div class="overlay"><i class="fas fa-upload text-white fs-3"></i></div>
                                            <input type="file" form="setting-form" name="company_logo" accept="image/*" class="d-none preview-input">
                                        </label>
                                        <div class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex flex-column flex-sm-row w-100 p-4 mt-4">
                                            <i class="fas fa-info-circle fs-3 text-primary me-4 mb-3 mb-sm-0"></i>
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1">Logo Guidelines</h5>
                                                <span><strong>Format:</strong> PNG (Transparent Background)<br><strong>Best Size:</strong> 250px Width &times; 60px Height<br>For optimum visibility on the frontend navbar.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 rounded-4 text-center">
                                <div class="card-body">
                                    <h5 class="fw-bolder mb-4">Login Screen Logo</h5>
                                    <div class="image-input-container">
                                        <label class="image-uploader shadow-sm" style="height: 150px; background-image: url('{{ isset($settings->login_logo) ? asset('storage/' . $settings->login_logo) : asset('isotope/metronic/img/isotope_p2.png') }}')">
                                            <div class="overlay"><i class="fas fa-upload text-white fs-3"></i></div>
                                            <input type="file" form="setting-form" name="login_logo" accept="image/*" class="d-none preview-input">
                                        </label>
                                        <div class="alert alert-dismissible bg-light-success border border-success border-dashed d-flex flex-column flex-sm-row w-100 p-4 mt-4">
                                            <i class="fas fa-check-circle fs-3 text-success me-4 mb-3 mb-sm-0"></i>
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1">Login Logo</h5>
                                                <span><strong>Format:</strong> PNG or SVG<br><strong>Best Size:</strong> 300px Width &times; 100px Height<br>A high-res logo that contrasts well against the login screen background.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 rounded-4 text-center">
                                <div class="card-body">
                                    <h5 class="fw-bolder mb-4">Favicon (Browser Icon)</h5>
                                    <div class="image-input-container">
                                        <label class="image-uploader shadow-sm" style="height: 150px; width: 150px; margin: 0 auto; background-image: url('{{ isset($settings->favicon) ? asset('storage/' . $settings->favicon) : asset('isotope/metronic/img/favicon.ico') }}')">
                                            <div class="overlay"><i class="fas fa-upload text-white fs-3"></i></div>
                                            <input type="file" form="setting-form" name="favicon" accept="image/*" class="d-none preview-input">
                                        </label>
                                        <div class="alert alert-dismissible bg-light-info border border-info border-dashed d-flex flex-column flex-sm-row w-100 p-4 mt-4">
                                            <i class="fas fa-globe fs-3 text-info me-4 mb-3 mb-sm-0"></i>
                                            <div class="d-flex flex-column pe-0 pe-sm-10 text-start">
                                                <h5 class="mb-1">Favicon</h5>
                                                <span><strong>Format:</strong> ICO or PNG<br><strong>Best Size:</strong> 64px Width &times; 64px Height<br>This icon will be displayed on the browser tabs.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-light-warning border-warning border border-dashed rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <h5 class="fw-bolder text-warning mb-0"><i class="fas fa-bullhorn text-warning me-2"></i> Promotional Popup</h5>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input type="hidden" form="setting-form" name="text[popup_status]" value="0">
                                            <input class="form-check-input" form="setting-form" type="checkbox" name="text[popup_status]" value="1" {{ (settings()->popup_status ?? 0) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark">Enable Popup</label>
                                        </div>
                                    </div>
                                    <div class="row g-5">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Popup URL (Link when clicked)</label>
                                            <input type="text" form="setting-form" name="text[popup_url]" class="form-control form-control-solid" value="{{ settings()->popup_url ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Popup Image</label>
                                            <label class="image-uploader shadow-sm w-100" style="height: 120px; background-image: url('{{ isset($settings->popup_image) ? asset('storage/' . $settings->popup_image) : '' }}')">
                                                <div class="overlay"><i class="fas fa-upload text-white fs-3"></i></div>
                                                <input type="file" form="setting-form" name="popup_image" accept="image/*" class="d-none preview-input">
                                            </label>
                                            <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex flex-column flex-sm-row w-100 p-4 mt-4">
                                                <i class="fas fa-exclamation-triangle fs-3 text-warning me-4 mb-3 mb-sm-0"></i>
                                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                                    <h5 class="mb-1">Popup Image Guidelines</h5>
                                                    <span><strong>Format:</strong> JPG / PNG<br><strong>Best Size:</strong> 600px &times; 600px (1:1 Ratio)<br>Use a visually striking image with clear text for promotions.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: PAGE BANNERS --}}
                <div class="tab-pane fade" id="tab_banners" role="tabpanel">
                    <div class="alert alert-primary bg-light-primary border-primary border-dashed d-flex align-items-center p-5 mb-8">
                        <i class="fas fa-info-circle fs-2 text-primary me-4"></i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-primary">Website Page Banners</h4>
                            <span><strong>Required Format:</strong> JPG / PNG (Dark overlays recommended)<br><strong>Best Dimensions:</strong> 1920px Width &times; 600px Height<br>Upload high-quality, wide aspect ratio images that will beautifully display at the top header of each corresponding page.</span>
                        </div>
                    </div>

                    <div class="row g-8">
                        @php
                            $banners = [
                                'landing_banner' => 'Landing Page Banners', // Main front banner
                                'services_banner' => 'Services Page Banner',
                                'products_banner' => 'Products/Apothecary Banner',
                                'gallery_banner' => 'Artistic Gallery Banner',
                                'about_banner' => 'About Team Banner',
                                'contact_banner' => 'Contact Page Banner'
                            ];
                        @endphp

                        @foreach($banners as $key => $label)
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-4">
                                    <div class="card-body">
                                        <h5 class="fw-bolder mb-3">{{ $label }}</h5>
                                        <div class="image-input-container">
                                            <label class="image-uploader shadow-sm w-100" style="height: 180px; background-image: url('{{ isset($settings->$key) ? asset('storage/' . $settings->$key) : '' }}')">
                                                <div class="overlay"><i class="fas fa-cloud-upload-alt text-white fs-1 mb-2"></i><span class="text-white fw-bold">Click to Upload</span></div>
                                                <input type="file" form="setting-form" name="{{ $key }}" accept="image/*" class="d-none preview-input">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </form> <!-- END FORM here because slider is ajax -->
                </div>

                {{-- TAB: SLIDER --}}
                <div class="tab-pane fade" id="tab_slider" role="tabpanel">
                    <div class="card bg-light-info border-info border border-dashed rounded-4 mb-8">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="fas fa-images text-info fs-1"></i>
                                    <div>
                                        <h4 class="text-info fw-bold mb-1">Dynamic Image Slider</h4>
                                        <span class="text-info text-opacity-75">Upload, drag to reorder, click ✕ to remove images.</span>
                                    </div>
                                </div>
                                <label for="slider-file-input" class="btn btn-info fw-bold">
                                    <i class="fas fa-plus me-1"></i> Add Slider Images
                                </label>
                                <input type="file" id="slider-file-input" multiple accept="image/*" class="d-none">
                            </div>

                            <div id="slider-upload-progress" class="mb-4 d-none">
                                <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-2 shadow-sm">
                                    <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                                    <span class="fs-8 fw-bold text-muted">Uploading images to server...</span>
                                </div>
                            </div>

                            <div id="slider-grid" class="d-flex flex-wrap gap-4 mt-5" style="min-height: 150px;">
                                @php
                                    $sliderImages = [];
                                    if (isset(settings()->slider_images)) {
                                        $decoded = json_decode(settings()->slider_images, true);
                                        if (is_array($decoded)) { $sliderImages = $decoded; }
                                    }
                                @endphp

                                @forelse($sliderImages as $img)
                                    <div class="slider-item position-relative shadow-sm" data-path="{{ $img }}">
                                        <img src="{{ asset('storage/' . $img) }}">
                                        <div class="overlay">
                                            <button type="button" class="btn btn-icon btn-danger btn-sm rounded-circle slider-delete-btn" data-path="{{ $img }}" title="Remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="drag-handle"><i class="fas fa-grip-horizontal"></i></div>
                                    </div>
                                @empty
                                    <div id="slider-empty-state" class="w-100 p-10 text-center bg-white rounded-3">
                                        <i class="fas fa-image text-muted display-4 opacity-25"></i>
                                        <div class="text-muted mt-4 fs-6">No slider images uploaded yet.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('css')
        <style>
            .image-uploader {
                display: block;
                position: relative;
                width: 100%;
                background-color: #f1f5f9;
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
                border-radius: 12px;
                border: 2px dashed #cbd5e1;
                cursor: pointer;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            .image-uploader:hover {
                border-color: #3b82f6;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
            }
            .image-uploader .overlay {
                position: absolute;
                inset: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .image-uploader:hover .overlay {
                opacity: 1;
            }
            .image-uploader.has-image {
                border-style: solid;
                border-color: transparent;
            }
            .image-uploader:not([style*="url('')"]):not([style*="url()"]) .overlay {
                background: rgba(0,0,0,0.3);
            }
            .image-uploader:not([style*="url('')"]):not([style*="url()"]) {
                border-style: solid;
            }
            
            /* Slider specific */
            .slider-item {
                width: 200px;
                height: 140px;
                border-radius: 12px;
                overflow: hidden;
                cursor: grab;
                transition: transform 0.2s;
            }
            .slider-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .slider-item .overlay {
                position: absolute;
                inset: 0;
                background: rgba(0,0,0,0.4);
                opacity: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.3s;
            }
            .slider-item:hover .overlay {
                opacity: 1;
            }
            .slider-item.sortable-ghost {
                opacity: 0.3;
                border: 2px dashed #0dcaf0;
            }
            .slider-item .drag-handle {
                position: absolute;
                bottom: 5px;
                width: 100%;
                text-align: center;
                color: white;
                opacity: 0.5;
                pointer-events: none;
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Image preview logic for all image uploaders
                document.querySelectorAll('.preview-input').forEach(input => {
                    input.addEventListener('change', function(e) {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            const uploader = this.closest('.image-uploader');
                            
                            reader.onload = function(e) {
                                uploader.style.backgroundImage = `url('${e.target.result}')`;
                                uploader.classList.add('has-image');
                            }
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                });

                // Slider logic stays same
                const uploadUrl = "{{ route(tenant() ? 'settings.slider.upload' : 'owner.settings.slider.upload') }}";
                const deleteUrl = "{{ route(tenant() ? 'settings.slider.delete' : 'owner.settings.slider.delete') }}";
                const reorderUrl = "{{ route(tenant() ? 'settings.slider.reorder' : 'owner.settings.slider.reorder') }}";
                const csrfToken = '{{ csrf_token() }}';
                const grid = document.getElementById('slider-grid');
                const progress = document.getElementById('slider-upload-progress');
                const fileInput = document.getElementById('slider-file-input');

                if (fileInput && grid) {
                    fileInput.addEventListener('change', function() {
                        if (!this.files.length) return;
                        const formData = new FormData();
                        [...this.files].forEach(f => formData.append('images[]', f));

                        progress.classList.remove('d-none');
                        fetch(uploadUrl, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                const empty = document.getElementById('slider-empty-state');
                                if (empty) empty.remove();
                                const newImages = data.images.slice(-(fileInput.files.length));
                                newImages.forEach(path => grid.appendChild(buildSliderItem(path)));
                            } else alert('Upload failed: ' + (data.message || 'Unknown error'));
                        })
                        .catch(e => alert('Error: ' + e))
                        .finally(() => { progress.classList.add('d-none'); fileInput.value = ''; });
                    });

                    function buildSliderItem(path) {
                        const div = document.createElement('div');
                        div.className = 'slider-item position-relative shadow-sm';
                        div.dataset.path = path;
                        div.innerHTML = `
                            <img src="/storage/${path}">
                            <div class="overlay">
                                <button type="button" class="btn btn-icon btn-danger btn-sm rounded-circle slider-delete-btn" data-path="${path}" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="drag-handle"><i class="fas fa-grip-horizontal"></i></div>`;
                        return div;
                    }

                    grid.addEventListener('click', function(e) {
                        const btn = e.target.closest('.slider-delete-btn');
                        if (!btn) return;
                        if (!confirm('Remove this image?')) return;
                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ path: btn.dataset.path })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                btn.closest('.slider-item').remove();
                                if (!grid.querySelector('.slider-item')) {
                                    grid.innerHTML = '<div id="slider-empty-state" class="w-100 p-10 text-center bg-white rounded-3"><i class="fas fa-image text-muted display-4 opacity-25"></i><div class="text-muted mt-4 fs-6">No slider images.</div></div>';
                                }
                            }
                        });
                    });

                    Sortable.create(grid, {
                        animation: 200,
                        ghostClass: 'sortable-ghost',
                        filter: '.slider-delete-btn',
                        onEnd: function() {
                            const order = [...grid.querySelectorAll('.slider-item')].map(el => el.dataset.path);
                            fetch(reorderUrl, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                body: JSON.stringify({ order })
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
