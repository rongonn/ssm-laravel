@extends('isotope::master')

@section('title', 'Settings')

@push('buttons')
<button type="submit" form="setting-form" class="btn btn-sm btn-isotope fw-bold">
    <i class="fas fa-paper-plane"></i>
    Save
</button>
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route(tenant() ? 'authorization.app-modules.update' : 'owner.authorization.app-modules.update') }}" method="POST" enctype="multipart/form-data" id="setting-form">
            @csrf
            <h5 class="text-center font-weight-bold">{{ __('Module Blocklist') }}</h5>
            <hr>
            @foreach ($modules as $module)
            <div class="col-lg-4">
                <div class="mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $module }}" {{
                            in_array($module, $blockedModules) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $module }}</label>
                    </div>
                </div>
            </div>
            @endforeach
        </form>
    </div>
</div>
@endsection