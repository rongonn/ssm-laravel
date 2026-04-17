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
        <div class="card card-flush h-md-100 {{ $unreadContacts > 0 ? 'bg-light-warning animate-pulse' : '' }}">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $unreadContacts }}</span>
                    <span class="text-gray-600 pt-1 fw-semibold fs-6">Unread Messages</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.contacts') }}" class="btn btn-sm btn-warning fw-bold">View Messages</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-md-4">
        <div class="card card-flush h-md-100 bg-light-primary">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-primary me-2 lh-1 ls-n2">{{ $newOrders }}</span>
                    <span class="text-primary pt-1 fw-semibold fs-6">New Orders</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.orders', ['status' => 'New Order']) }}" class="btn btn-sm btn-primary fw-bold">Review Orders</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush h-md-100 bg-light-info">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-info me-2 lh-1 ls-n2">{{ $shippedOrders }}</span>
                    <span class="text-info pt-1 fw-semibold fs-6">Orders Shipped</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.orders', ['status' => 'Shipped']) }}" class="btn btn-sm btn-info fw-bold">Track Shipment</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush h-md-100 bg-light-success">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-success me-2 lh-1 ls-n2">{{ $totalOrders }}</span>
                    <span class="text-success pt-1 fw-semibold fs-6">Total Orders</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-success fw-bold">All Orders History</a>
            </div>
        </div>
    </div>
</div>
@endsection
