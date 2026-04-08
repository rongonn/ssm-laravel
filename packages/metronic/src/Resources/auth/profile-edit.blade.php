@extends('isotope::master')

@section('title', 'Profile Edit')

@push('buttons')
<button type="submit" class="btn btn-flat btn-sm btn-isotope" form="form">
    <i class="fa-solid fa-save"></i>
    @lang('Update')
</button>
@endpush

@section('content')

<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">
        <div class="row">
            <div class="col-md-2">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img class="object-fit-cover"
                            src="{{ $user->avatarImage ? Storage::url($user->avatarImage->path) : asset('isotope/metronic/img/blank.png') }}"
                            alt="image" />
                        <div
                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $user->name }}</span>
                </div>
                <div class="d-flex flex-wrap fw-semibold fs-6 pe-2">
                    <div class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                        <i class="fa-solid fa-envelope mx-2"></i>
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route(tenant() ? 'profile.update' : 'owner.profile.update') }}" method="post" id="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profile Edit</h3>
            </div>
        </div>
        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">@lang('Avatar')</label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline" data-kt-image-input="true">
                        <div class="image-input-wrapper w-125px h-125px"
                            style="background-image: url({{ $user->avatarImage ? Storage::url($user->avatarImage->path) : asset('isotope/metronic/img/blank.png') }})">
                        </div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="avatar_remove" />
                        </label>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                    </div>
                    <div class="help-block with-errors mt-2 text-primary">@lang('Avatar image')</div>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">@lang('Full name')</label>
                <div class="col-lg-8">
                    <input type="text" name="name" class="form-control form-control-sm"
                        placeholder="Full Name" value="{{ $user->name ?? '' }}" required>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">@lang('Email Address')</label>
                <div class="col-lg-8">
                    <input type="text" name="email" class="form-control form-control-sm"
                        placeholder="Email Addresss" value="{{ $user->email ?? '' }}" required>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection