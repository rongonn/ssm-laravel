@extends('isotope::master')

@section('title', 'Dashboard')

@section('content')
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $serviceCount }}</span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Services Available</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.services') }}" class="btn btn-sm btn-light-primary fw-bold">View Services</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $productCount }}</span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Active Products</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.products') }}" class="btn btn-sm btn-light-success fw-bold">View Inventory</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $teamCount }}</span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Team Members</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.team') }}" class="btn btn-sm btn-light-info fw-bold">Manage Staff</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $contactCount }}</span>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Inquiries</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="/admin/contacts" class="btn btn-sm btn-light-warning fw-bold">See Messages</a>
            </div>
        </div>
    </div>
</div>
@endsection
