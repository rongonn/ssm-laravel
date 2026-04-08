@extends('isotope::master')

@section('title', 'Profile')

@push('buttons')
    <a href="{{ route(tenant() ? 'profile.edit' : 'owner.profile.edit') }}"
        class="btn btn-sm bg-success-emphasis text-white align-self-center me-2">
        <i class="fa-solid fa-edit text-white"></i>
        @lang('Edit profile')
    </a>
    <a href="{{ route(tenant() ? 'showChangePasswordForm' : 'owner.showChangePasswordForm') }}" class="btn btn-sm btn-isotope align-self-center">
        <i class="fa-solid fa-key text-white"></i>
        @lang('Change passoword')
    </a>
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
@endsection