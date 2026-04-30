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
                    @foreach(['Website', 'Manual'] as $source)
                    <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
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
                    <th class="min-w-150px">Items</th>
                    <th class="min-w-70px">Total</th>
                    <th class="min-w-100px">Source</th>
                    <th class="min-w-100px">Status</th>
                    <th class="min-w-100px">Order Date</th>
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
                        <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="modal" data-bs-target="#modal_view_order_{{ $order->id }}">
                            <div class="symbol-group symbol-hover mb-1">
                                @foreach($order->items->take(3) as $item)
                                <div class="symbol symbol-35px symbol-circle" title="{{ $item->product->name }}">
                                    <img src="{{ $item->product->main_image ?? 'https://placehold.co/35x35' }}" alt="img" />
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-primary text-primary fs-8 fw-bold">+{{ $order->items->count() - 3 }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="ms-3">
                                <span class="text-gray-800 fw-bold d-block fs-7">{{ $order->items->count() }} item(s)</span>
                            </div>
                        </div>
                    </td>
                    <td>৳{{ number_format($order->total_price, 2) }}</td>
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
                    <td>
                        <span >
                            {{ $order->created_at->format('d-M-Y h:i A') }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-icon btn-light-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal_view_order_{{ $order->id }}">
                                <i class="bi bi-eye fs-4"></i>
                            </button>
                            <button class="btn btn-icon btn-light-warning btn-sm edit-order-btn" 
                                data-order="{{ json_encode($order) }}" 
                                data-items="{{ json_encode($order->items->map(function($i){ 
                                    return [
                                        'id' => $i->product_id,
                                        'name' => $i->product->name,
                                        'price' => $i->price,
                                        'image' => $i->product->main_image,
                                        'quantity' => $i->quantity
                                    ]; 
                                })) }}">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_status_{{ $order->id }}">
                                Status
                            </button>
                            {{-- <form action="{{ route('admin.delete', ['table' => 'orders', 'id' => $order->id]) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-light-danger btn-sm">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form> --}}
                        </div>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        @foreach($orders as $order)
                {{-- View Modal --}}
                <div class="modal fade" id="modal_view_order_{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-800px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Order Details #{{ $order->order_no }}</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="bi bi-x fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body py-10 px-lg-17">
                                <div class="row mb-7">
                                    <div class="col-md-6">
                                        <h4 class="fw-bold text-gray-800 mb-3">Customer Information</h4>
                                        <div class="d-flex flex-column gap-1">
                                            <div class="fw-bold fs-6 text-gray-800">{{ $order->customer_name }}</div>
                                            <div class="text-gray-600 fs-7">{{ $order->customer_phone }}</div>
                                            <div class="text-gray-600 fs-7 whitespace-pre-wrap">{{ $order->customer_address }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end">
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
                                        <h4 class="fw-bold text-gray-800 mb-3">Order Status</h4>
                                        <span class="badge badge-light-{{ $badgeColor }} fs-6">{{ $order->status }}</span>
                                        <div class="text-muted fs-7 mt-2">Placed on {{ $order->created_at->format('d M, Y h:i A') }}</div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Product</th>
                                                <th class="text-center">QTY</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <img src="{{ $item->product->main_image ?? 'https://placehold.co/40x40' }}" alt="img" />
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-800 fw-bold">{{ $item->product->name }}</span>
                                                            <span class="text-muted fs-7">{{ $item->product->brand }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">৳{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="d-flex justify-content-end">
                                    <div class="mw-300px w-100">
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-semibold text-gray-600 fs-7">Subtotal:</div>
                                            <div class="fw-bold text-gray-800">৳{{ number_format($order->subtotal, 2) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-semibold text-gray-600 fs-7">Delivery Charge:</div>
                                            <div class="fw-bold text-gray-800">৳{{ number_format($order->delivery_charge, 2) }}</div>
                                        </div>
                                        <div class="d-flex flex-stack mb-3">
                                            <div class="fw-semibold text-gray-600 fs-7">Discount:</div>
                                            <div class="fw-bold text-danger">-৳{{ number_format($order->discount, 2) }}</div>
                                        </div>
                                        <div class="separator separator-dashed my-3"></div>
                                        <div class="d-flex flex-stack">
                                            <div class="fw-bolder text-gray-800 fs-5">Total:</div>
                                            <div class="fw-bolder text-brand-900 fs-4">৳{{ number_format($order->total_price, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Modal --}}
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
        @endforeach

        <!-- Pagination -->
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries</div>
            <ul class="pagination">
                {{ $orders->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Manual Order Modal -->
<div class="modal fade" id="modal_add_manual_order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <form action="{{ route('admin.orders.manual') }}" method="POST" id="manual_order_form">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Create Manual Order</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="row g-9 mb-7">
                        <div class="col-md-6">
                            <h4 class="fw-bold text-gray-800 mb-5">Customer Details</h4>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Name</label>
                                <input type="text" class="form-control form-control-solid" name="customer_name" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Phone</label>
                                <input type="text" class="form-control form-control-solid" name="customer_phone" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Delivery Address</label>
                                <textarea class="form-control form-control-solid" name="customer_address" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold text-gray-800 mb-5">Add Products</h4>
                            <div class="fv-row mb-7">
                                <select id="product_selector" class="form-select form-select-solid" data-control="select2" data-placeholder="Search product...">
                                    <option value=""></option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->main_image ?? 'https://placehold.co/40x40' }}">
                                        {{ $product->name }} (৳{{ $product->price }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="table-responsive mb-7" style="max-height: 300px; overflow-y: auto;">
                                <table class="table align-middle table-row-dashed fs-7 gy-3" id="selected_products_table">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                            <th>Product</th>
                                            <th class="w-80px">QTY</th>
                                            <th class="text-end">Total</th>
                                            <th class="w-40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="manual_order_items">
                                        <!-- Items will be added here via JS -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="bg-light p-5 rounded-3">
                                <div class="d-flex flex-stack mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="fw-bold">৳<span id="subtotal_display">0.00</span></span>
                                    <input type="hidden" name="subtotal" id="subtotal_input" value="0">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="fs-8 fw-semibold mb-1">Delivery Charge</label>
                                        <input type="number" name="delivery_charge" class="form-control form-control-sm form-control-solid calc-trigger" value="0" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <label class="fs-8 fw-semibold mb-1">Discount</label>
                                        <input type="number" name="discount" class="form-control form-control-sm form-control-solid calc-trigger" value="0" step="0.01">
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <span class="fw-bolder text-gray-800">Total:</span>
                                    <span class="fw-bolder text-primary fs-5">৳<span id="total_display">0.00</span></span>
                                    <input type="hidden" name="total_price" id="total_input" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="submit" class="btn btn-primary">Place Manual Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Manual Order Modal -->
<div class="modal fade" id="modal_edit_manual_order" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <form action="" method="POST" id="edit_order_form">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Edit Order <span id="edit_order_no_display"></span></h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="row g-9 mb-7">
                        <div class="col-md-6">
                            <h4 class="fw-bold text-gray-800 mb-5">Customer Details</h4>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Name</label>
                                <input type="text" class="form-control form-control-solid" name="customer_name" id="edit_customer_name" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Customer Phone</label>
                                <input type="text" class="form-control form-control-solid" name="customer_phone" id="edit_customer_phone" required />
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Delivery Address</label>
                                <textarea class="form-control form-control-solid" name="customer_address" id="edit_customer_address" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold text-gray-800 mb-5">Edit Products</h4>
                            <div class="fv-row mb-7">
                                <select id="edit_product_selector" class="form-select form-select-solid" data-control="select2" data-placeholder="Search product...">
                                    <option value=""></option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->main_image ?? 'https://placehold.co/40x40' }}">
                                        {{ $product->name }} (৳{{ $product->price }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="table-responsive mb-7" style="max-height: 300px; overflow-y: auto;">
                                <table class="table align-middle table-row-dashed fs-7 gy-3">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                            <th>Product</th>
                                            <th class="w-80px">QTY</th>
                                            <th class="text-end">Total</th>
                                            <th class="w-40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="edit_order_items_container">
                                        <!-- Items will be added here via JS -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="bg-light p-5 rounded-3">
                                <div class="d-flex flex-stack mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="fw-bold">৳<span id="edit_subtotal_display">0.00</span></span>
                                    <input type="hidden" name="subtotal" id="edit_subtotal_input" value="0">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="fs-8 fw-semibold mb-1">Delivery Charge</label>
                                        <input type="number" name="delivery_charge" id="edit_delivery_charge" class="form-control form-control-sm form-control-solid edit-calc-trigger" value="0" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <label class="fs-8 fw-semibold mb-1">Discount</label>
                                        <input type="number" name="discount" id="edit_discount" class="form-control form-control-sm form-control-solid edit-calc-trigger" value="0" step="0.01">
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <span class="fw-bolder text-gray-800">Total:</span>
                                    <span class="fw-bolder text-primary fs-5">৳<span id="edit_total_display">0.00</span></span>
                                    <input type="hidden" name="total_price" id="edit_total_input" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    let orderItems = {};
    let editOrderItems = {};

    // --- ADD MODAL LOGIC ---
    $('#product_selector').on('change', function() {
        const productId = $(this).val();
        if (!productId) return;
        const option = $(this).find('option:selected');
        if (orderItems[productId]) {
            orderItems[productId].quantity++;
        } else {
            orderItems[productId] = {
                id: productId,
                name: option.data('name'),
                price: parseFloat(option.data('price')),
                image: option.data('image'),
                quantity: 1
            };
        }
        renderAddItems();
        $(this).val('').trigger('change');
    });

    function renderAddItems() {
        const container = $('#manual_order_items');
        container.empty();
        let subtotal = 0;
        Object.values(orderItems).forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            container.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.image}" class="w-30px h-30px rounded me-2" />
                            <span class="text-gray-800 fw-bold text-truncate" style="max-width: 120px;">${item.name}</span>
                        </div>
                        <input type="hidden" name="items[${item.id}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${item.id}][price]" value="${item.price}">
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-icon btn-xs btn-light-primary add-qty-btn" data-id="${item.id}" data-action="minus"><i class="bi bi-dash fs-9"></i></button>
                            <input type="number" name="items[${item.id}][quantity]" class="form-control form-control-sm form-control-solid p-1 text-center w-40px" value="${item.quantity}" readonly>
                            <button type="button" class="btn btn-icon btn-xs btn-light-primary add-qty-btn" data-id="${item.id}" data-action="plus"><i class="bi bi-plus fs-9"></i></button>
                        </div>
                    </td>
                    <td class="text-end fw-bold">৳${itemTotal.toFixed(2)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-icon btn-xs btn-light-danger add-remove-item" data-id="${item.id}"><i class="bi bi-x fs-7"></i></button>
                    </td>
                </tr>
            `);
        });
        $('#subtotal_display').text(subtotal.toFixed(2));
        $('#subtotal_input').val(subtotal);
        calculateAddTotal();
    }

    $(document).on('click', '.add-qty-btn', function() {
        const id = $(this).data('id');
        if ($(this).data('action') === 'plus') orderItems[id].quantity++;
        else if (orderItems[id].quantity > 1) orderItems[id].quantity--;
        renderAddItems();
    });

    $(document).on('click', '.add-remove-item', function() {
        delete orderItems[$(this).data('id')];
        renderAddItems();
    });

    $('.calc-trigger').on('input', calculateAddTotal);
    function calculateAddTotal() {
        const total = (parseFloat($('#subtotal_input').val()) || 0) + (parseFloat($('input[name="delivery_charge"]').val()) || 0) - (parseFloat($('input[name="discount"]').val()) || 0);
        $('#total_display').text(total.toFixed(2));
        $('#total_input').val(total);
    }

    // --- EDIT MODAL LOGIC ---
    $('.edit-order-btn').on('click', function() {
        const order = $(this).data('order');
        const items = $(this).data('items');
        
        $('#edit_order_form').attr('action', `/admin/orders/${order.id}/manual`);
        $('#edit_order_no_display').text(`#${order.order_no}`);
        $('#edit_customer_name').val(order.customer_name);
        $('#edit_customer_phone').val(order.customer_phone);
        $('#edit_customer_address').val(order.customer_address);
        $('#edit_delivery_charge').val(order.delivery_charge);
        $('#edit_discount').val(order.discount);

        editOrderItems = {};
        items.forEach(item => {
            editOrderItems[item.id] = item;
        });

        renderEditItems();
        $('#modal_edit_manual_order').modal('show');
    });

    $('#edit_product_selector').on('change', function() {
        const productId = $(this).val();
        if (!productId) return;
        const option = $(this).find('option:selected');
        if (editOrderItems[productId]) {
            editOrderItems[productId].quantity++;
        } else {
            editOrderItems[productId] = {
                id: productId,
                name: option.data('name'),
                price: parseFloat(option.data('price')),
                image: option.data('image'),
                quantity: 1
            };
        }
        renderEditItems();
        $(this).val('').trigger('change');
    });

    function renderEditItems() {
        const container = $('#edit_order_items_container');
        container.empty();
        let subtotal = 0;
        Object.values(editOrderItems).forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            container.append(`
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.image}" class="w-30px h-30px rounded me-2" />
                            <span class="text-gray-800 fw-bold text-truncate" style="max-width: 120px;">${item.name}</span>
                        </div>
                        <input type="hidden" name="items[${item.id}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${item.id}][price]" value="${item.price}">
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-icon btn-xs btn-light-primary edit-qty-btn" data-id="${item.id}" data-action="minus"><i class="bi bi-dash fs-9"></i></button>
                            <input type="number" name="items[${item.id}][quantity]" class="form-control form-control-sm form-control-solid p-1 text-center w-40px" value="${item.quantity}" readonly>
                            <button type="button" class="btn btn-icon btn-xs btn-light-primary edit-qty-btn" data-id="${item.id}" data-action="plus"><i class="bi bi-plus fs-9"></i></button>
                        </div>
                    </td>
                    <td class="text-end fw-bold">৳${itemTotal.toFixed(2)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-icon btn-xs btn-light-danger edit-remove-item" data-id="${item.id}"><i class="bi bi-x fs-7"></i></button>
                    </td>
                </tr>
            `);
        });
        $('#edit_subtotal_display').text(subtotal.toFixed(2));
        $('#edit_subtotal_input').val(subtotal);
        calculateEditTotal();
    }

    $(document).on('click', '.edit-qty-btn', function() {
        const id = $(this).data('id');
        if ($(this).data('action') === 'plus') editOrderItems[id].quantity++;
        else if (editOrderItems[id].quantity > 1) editOrderItems[id].quantity--;
        renderEditItems();
    });

    $(document).on('click', '.edit-remove-item', function() {
        delete editOrderItems[$(this).data('id')];
        renderEditItems();
    });

    $('.edit-calc-trigger').on('input', calculateEditTotal);
    function calculateEditTotal() {
        const total = (parseFloat($('#edit_subtotal_input').val()) || 0) + (parseFloat($('#edit_delivery_charge').val()) || 0) - (parseFloat($('#edit_discount').val()) || 0);
        $('#edit_total_display').text(total.toFixed(2));
        $('#edit_total_input').val(total);
    }
});
</script>
@endpush
@endsection
