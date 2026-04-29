    @extends('isotope::master')

@section('title', 'Manage Products')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.products') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Products" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_product">
                <i class="bi bi-plus fs-2"></i>Add Product
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_products_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Product</th>
                    <th class="min-w-70px">Category</th>
                    <th class="min-w-70px">Price</th>
                    <th class="min-w-70px">Status</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($products as $product)
                @php $allImages = is_array($product->image_url) ? $product->image_url : []; $mainImage = $allImages[0] ?? 'https://placehold.co/50x50'; @endphp
                <tr>
                    <td class="d-flex align-items-center">
                        <div class="symbol symbol-50px overflow-hidden me-3">
                            <div class="symbol-label">
                                <img src="{{ $mainImage }}" alt="{{ $product->name }}" class="w-100" style="object-fit:cover;" />
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold mb-1">{{ $product->name }}</span>
                            <span class="text-gray-400 fw-bold fs-7">{{ $product->brand }}</span>
                        </div>
                    </td>
                    <td>{{ $product->categoryItem ? $product->categoryItem->name : $product->category }}</td>
                    <td>৳{{ $product->price }}</td>
                    <td>
                        <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light-{{ $product->is_active ? 'success' : 'danger' }}">
                                {{ $product->is_active ? 'Active' : 'Hidden' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_view_product_{{ $product->id }}"
                                    title="View">
                                <i class="bi bi-eye fs-4"></i>
                            </button>
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_edit_product_{{ $product->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'products', 'id' => $product->id]) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- View Modal --}}
                <div class="modal fade" id="kt_modal_view_product_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-900px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Product Details</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="bi bi-x fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body py-10 px-lg-17">
                                <div class="row g-9 mb-7">
                                    <div class="col-md-6">
                                        <div class="mb-5">
                                            <label class="fw-black text-muted text-uppercase fs-7 mb-2">Product Name</label>
                                            <div class="fw-bold fs-3 text-gray-800">{{ $product->name }}</div>
                                        </div>
                                        <div class="row mb-5">
                                            <div class="col-6">
                                                <label class="fw-black text-muted text-uppercase fs-7 mb-2">Brand</label>
                                                <div class="fw-bold fs-5 text-gray-800">{{ $product->brand ?? 'N/A' }}</div>
                                            </div>
                                            <div class="col-6">
                                                <label class="fw-black text-muted text-uppercase fs-7 mb-2">Price</label>
                                                <div class="fw-bold fs-5 text-brand-900">৳{{ $product->price }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="fw-black text-muted text-uppercase fs-7 mb-2">Category</label>
                                            <div class="fw-bold fs-6 text-gray-800">
                                                <span class="badge badge-light-primary">{{ $product->categoryItem ? $product->categoryItem->name : ($product->category ?? 'N/A') }}</span>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="fw-black text-muted text-uppercase fs-7 mb-2">Description</label>
                                            <div class="text-gray-600 fs-6 whitespace-pre-wrap">{{ $product->description ?? 'No description provided.' }}</div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="fw-black text-muted text-uppercase fs-7 mb-2">Status</label>
                                            <div>
                                                <span class="badge badge-{{ $product->is_active ? 'success' : 'danger' }}">{{ $product->is_active ? 'Active' : 'Hidden' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-black text-muted text-uppercase fs-7 mb-5 d-block">Images Gallery</label>
                                        <div class="row g-3">
                                            @forelse($allImages as $index => $imgUrl)
                                            <div class="col-4">
                                                <div class="symbol symbol-100px symbol-lg-120px overflow-hidden border rounded-3 position-relative">
                                                    <img src="{{ $imgUrl }}" class="w-100 h-100 object-cover" alt="Image" />
                                                    @if($index === 0)
                                                    <span class="position-absolute top-0 start-0 badge badge-primary p-1 fs-9">Main</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-12">
                                                <div class="bg-light p-5 rounded-3 text-center text-muted">No images found</div>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer flex-center">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_product_{{ $product->id }}">Edit Product</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="kt_modal_edit_product_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-750px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'products', 'id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Product</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Product Name</label>
                                        <input type="text" class="form-control form-control-solid" name="name" value="{{ $product->name }}" required />
                                    </div>
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Brand</label>
                                            <input type="text" class="form-control form-control-solid" name="brand" value="{{ $product->brand }}" />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Price (৳)</label>
                                            <input type="number" class="form-control form-control-solid" name="price" value="{{ $product->price }}" required />
                                        </div>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Category</label>
                                        <select class="form-select form-select-solid" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Description</label>
                                        <textarea class="form-control form-control-solid" name="description" rows="3">{{ $product->description }}</textarea>
                                    </div>

                                    {{-- Current Images + Remove --}}
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-3">Current Images ({{ count($allImages) }}/5) — First is main image</label>
                                        <div class="d-flex flex-wrap gap-3 mb-4" id="img-grid-{{ $product->id }}">
                                            @forelse($allImages as $imgUrl)
                                            <div class="position-relative" style="width:90px; height:90px;">
                                                <img src="{{ $imgUrl }}" class="w-100 h-100 rounded-2 border {{ $loop->first ? 'border-primary border-2' : 'border' }}" style="object-fit:cover;" />
                                                @if($loop->first)<span class="position-absolute top-0 start-0 badge badge-primary" style="font-size:9px;">Main</span>@endif
                                                <button 
                                                    type="button"
                                                    class="btn btn-icon btn-danger btn-xs rounded-circle p-0 remove-img-btn"
                                                    style="position:absolute;top:2px;right:2px;width:20px;height:20px;font-size:10px;"
                                                    data-product-id="{{ $product->id }}"
                                                    data-url="{{ $imgUrl }}"
                                                    data-csrf="{{ csrf_token() }}"
                                                    data-route="{{ route('admin.products.image.remove', $product->id) }}"
                                                    title="Remove">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                            @empty
                                            <span class="text-muted">No images yet.</span>
                                            @endforelse
                                        </div>

                                        {{-- Add Image --}}
                                        @if(count($allImages) < 5)
                                        <label class="fs-6 fw-semibold mb-2">Add More Images ({{ 5 - count($allImages) }} slot{{ (5 - count($allImages)) > 1 ? 's' : '' }} left)</label>
                                        <input type="file" name="images[]" class="form-control form-control-solid" accept="image/*" multiple onchange="previewNewImages(this, 'new-preview-{{ $product->id }}')" />
                                        <div class="d-flex flex-wrap gap-2 mt-3" id="new-preview-{{ $product->id }}"></div>
                                        @else
                                        <div class="alert alert-warning py-2 px-4 fs-7">Maximum 5 images reached. Remove one to add more.</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer flex-center">
                                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries</div>
            <ul class="pagination">
                {{ $products->links() }}
            </ul>
        </div>
    </div>
</div>

{{-- Add Product Modal --}}
<div class="modal fade" id="kt_modal_add_product" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'products']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Product</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Product Name</label>
                        <input type="text" class="form-control form-control-solid" name="name" required />
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Brand</label>
                            <input type="text" class="form-control form-control-solid" name="brand" placeholder="e.g. L'Oreal" />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Price (৳)</label>
                            <input type="number" class="form-control form-control-solid" name="price" required />
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Category</label>
                        <select class="form-select form-select-solid" name="category_id">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Description</label>
                        <textarea class="form-control form-control-solid" name="description" rows="3"></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Images (Max 5 — first will be main image)</label>
                        <input type="file" name="images[]" class="form-control form-control-solid" accept="image/*" multiple onchange="previewNewImages(this, 'add-preview')" />
                        <div class="d-flex flex-wrap gap-2 mt-3" id="add-preview"></div>
                        <div class="text-muted fs-8 mt-2">Select up to 5 images. First selected = main image.</div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
function previewNewImages(input, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    const maxFiles = 5;
    const files = Array.from(input.files).slice(0, maxFiles);

    if (input.files.length > maxFiles) {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative';
            wrapper.style = 'width:80px;height:80px;';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-100 h-100 rounded-2 border' + (index === 0 ? ' border-primary border-2' : '');
            img.style = 'object-fit:cover;';

            if (index === 0) {
                const badge = document.createElement('span');
                badge.className = 'position-absolute top-0 start-0 badge badge-primary';
                badge.style = 'font-size:9px;';
                badge.textContent = 'Main';
                wrapper.appendChild(badge);
            }

            wrapper.appendChild(img);
            container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

// AJAX image removal — avoids nested <form> which breaks submit
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.remove-img-btn');
    if (!btn) return;

    if (!confirm('Remove this image?')) return;

    const route = btn.dataset.route;
    const csrf  = btn.dataset.csrf;
    const url   = btn.dataset.url;
    const card  = btn.closest('.position-relative');

    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ url: url })
    })
    .then(r => r.json ? r : r)
    .then(() => {
        // Remove the thumbnail card from DOM
        if (card) card.remove();
    })
    .catch(() => { window.location.reload(); });
});
</script>
@endpush
@endsection
