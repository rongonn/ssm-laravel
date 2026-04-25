@extends('isotope::master')

@section('title', 'Manage Orders')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <form action="{{ route('admin.orders') }}" method="GET" class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="bi bi-search position-absolute ms-4"></i>
                    <input type="text" name="search" class="form-control form-control-solid w-200px ps-12" placeholder="Search Order/Customer" value="{{ request('search') }}" />
                </div>
                
                <select name="status" class="form-select form-select-solid w-150px" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>

                <select name="source" class="form-select form-select-solid w-150px" onchange="this.form.submit()">
                    <option value="">All Sources</option>
                    @foreach($sources as $source)
                    <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                    @endforeach
                </select>

                <select name="product_id" class="form-select form-select-solid w-200px" onchange="this.form.submit()">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>

                <a href="{{ route('admin.orders') }}" class="btn btn-light-primary btn-sm">Reset</a>
            </form>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_manual_order">
                <i class="bi bi-plus-lg fs-3"></i> Manual Order
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Order No</th>
                    <th class="min-w-150px">Customer</th>
                    <th class="min-w-150px">Product</th>
                    <th class="min-w-70px">Total</th>
                    <th class="min-w-100px">Source</th>
                    <th class="min-w-100px">Status</th>
                    <th class="text-end min-w-150px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($orders as $order)
                <tr>
                    <td class="fw-bold text-gray-800">{{ $order->order_no ?? 'N/A' }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">{{ $order->customer_name }}</span>
                            <span class="text-muted fs-7">{{ $order->customer_phone }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="modal" data-bs-target="#modal_product_{{ $order->id }}">
                            <div class="symbol symbol-40px me-3">
                                <img src="{{ $order->product->image_url ?? 'https://placehold.co/40x40' }}" alt="{{ $order->product->name }}" />
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 text-hover-primary fw-bold">{{ $order->product->name }}</span>
                                <span class="text-muted fs-7">{{ $order->product->brand }}</span>
                            </div>
                        </div>
                    </td>
                    <td>৳{{ $order->total_price }}</td>
                    <td>
                        <span class="badge badge-light-{{ $order->source == 'Manual' ? 'warning' : 'primary' }} fw-bold">{{ $order->source ?? 'Website' }}</span>
                    </td>
                    <td>
                        @php
                            $badgeColor = match($order->status) {
                                'New Order' => 'primary',
                                'Pending' => 'secondary',
                                'Pending Payment' => 'light-danger',
                                'Processing' => 'warning',
                                'Shipped' => 'info',
                                'Delivered' => 'success',
                                'Cancelled' => 'danger',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="badge badge-light-{{ $badgeColor }}">{{ $order->status }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal_edit_order_{{ $order->id }}">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_status_{{ $order->id }}">
                                Status
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Product Details Modal -->
                <div class="modal fade" id="modal_product_{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-500px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Ordered Product Details</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="bi bi-x fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body py-10 px-lg-17">
                                <div class="text-center mb-7">
                                    <img src="{{ $order->product->image_url ?? 'https://placehold.co/200x200' }}" class="w-150px h-150px rounded-3 shadow-sm object-fit-cover" />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="fw-bold fs-6 text-gray-400 uppercase tracking-widest text-xs">Product Name</label>
                                    <div class="fs-4 fw-bold text-gray-800">{{ $order->product->name }}</div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-6">
                                        <label class="fw-bold fs-6 text-gray-400 uppercase tracking-widest text-xs">Brand</label>
                                        <div class="fs-6 fw-bold text-gray-800">{{ $order->product->brand ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-6">
                                        <label class="fw-bold fs-6 text-gray-400 uppercase tracking-widest text-xs">Category</label>
                                        <div class="fs-6 fw-bold text-gray-800">{{ $order->product->categoryItem->name ?? $order->product->category }}</div>
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="fw-bold fs-6 text-gray-400 uppercase tracking-widest text-xs">Description</label>
                                    <div class="fs-7 text-gray-600">{{ $order->product->description }}</div>
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                                <div class="fv-row">
                                    <label class="fw-bold fs-6 text-gray-400 uppercase tracking-widest text-xs">Delivery Address</label>
                                    <div class="fs-6 text-gray-800 p-4 bg-light rounded-2 border border-dashed border-gray-300">
                                        {{ $order->customer_address }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Modal -->
                <div class="modal fade" id="modal_status_{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-400px">
                        <div class="modal-content">
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Update Order Status</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Select Status</label>
                                        <select name="status" class="form-select form-select-solid">
                                            @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer flex-center">
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Order Modal -->
                <div class="modal fade" id="modal_edit_order_{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-500px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'orders', 'id' => $order->id]) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Customer Info</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Customer Name</label>
                                        <input type="text" class="form-control form-control-solid" name="customer_name" value="{{ $order->customer_name }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Customer Phone</label>
                                        <input type="text" class="form-control form-control-solid" name="customer_phone" value="{{ $order->customer_phone }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Delivery Address</label>
                                        <textarea class="form-control form-control-solid" name="customer_address" rows="3" required>{{ $order->customer_address }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Total Price</label>
                                        <input type="number" step="0.01" class="form-control form-control-solid" name="total_price" value="{{ $order->total_price }}" required />
                                    </div>
                                </div>
                                <div class="modal-footer flex-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Add Manual Order Modal -->
        <div class="modal fade" id="modal_add_manual_order" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-600px">
                <div class="modal-content">
                    <form action="{{ route('admin.orders.manual') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h2 class="fw-bold">Create Manual Order</h2>
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="bi bi-x fs-1"></i>
                            </div>
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Select Product</label>
                                <select name="product_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select a product" required>
                                    <option value=""></option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (৳{{ $product->price }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Name</label>
                                <input type="text" class="form-control form-control-solid" name="customer_name" placeholder="Full Name" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Phone</label>
                                <input type="text" class="form-control form-control-solid" name="customer_phone" placeholder="Phone Number" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Delivery Address</label>
                                <textarea class="form-control form-control-solid" name="customer_address" rows="3" placeholder="Full Address" required></textarea>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Total Price (৳)</label>
                                <input type="number" step="0.01" class="form-control form-control-solid" name="total_price" placeholder="Amount to pay" required />
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="submit" class="btn btn-primary">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries</div>
            <ul class="pagination">
                {{ $orders->links() }}
            </ul>
        </div>
    </div>
</div>
@endsection
