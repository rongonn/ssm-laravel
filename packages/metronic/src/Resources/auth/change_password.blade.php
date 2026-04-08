@extends('isotope::master')

@section('title', 'Change Password')

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
                        <img class="object-fit-cover" src="{{ $user->avatarImage ? Storage::url($user->avatarImage->path) : asset('isotope/metronic/img/blank.png') }}" alt="image" />
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

<form action="{{ route(tenant() ? 'updatePassword' : 'owner.updatePassword') }}" method="post" id="form" enctype="multipart/form-data">
    @csrf
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">@lang('Change password')</h3>
            </div>
        </div>
        <div class="card-body p-9">
            <div class="row mb-2">
                <label class="col-lg-4 fw-semibold text-muted">@lang('Current passoword')</label>
                <div class="col-lg-8">
                    <input type="password" name="current_password" class="form-control form-control-sm"
                        placeholder="@lang('Current passoword')" required>
                </div>
            </div>
            <div class="row mb-2 mt-5">
                <label class="col-lg-4 fw-semibold text-muted">@lang('New passoword')</label>
                <div class="col-lg-8">
                    <input type="password" name="password" class="form-control form-control-sm"
                        placeholder="@lang('New passoword')" required>
                </div>
            </div>
            <div class="row mb-2 mt-5">
                <label class="col-lg-4 fw-semibold text-muted">@lang('Confirm password')</label>
                <div class="col-lg-8">
                    <input type="password" name="password_confirmation" class="form-control form-control-sm"
                        placeholder="@lang('Confirm passoword')" required>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection