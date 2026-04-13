@extends('isotope::master')

@section('title', 'Manage Services')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.services') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Services" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_service">
                <i class="bi bi-plus fs-2"></i>Add Service
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_services_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Service</th>
                    <th class="min-w-70px">Category</th>
                    <th class="min-w-70px">Price</th>
                    <th class="min-w-70px">Duration</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($services as $service)
                <tr>
                    <td class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <a href="#">
                                <div class="symbol-label">
                                    <img src="{{ $service->image_url ?? 'https://placehold.co/50x50' }}" alt="{{ $service->name }}" class="w-100" />
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $service->name }}</a>
                            <span>{{ Str::limit($service->description, 50) }}</span>
                        </div>
                    </td>
                    <td>{{ $service->category }}</td>
                    <td>৳{{ $service->price }}</td>
                    <td>{{ $service->duration }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_edit_service_{{ $service->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'services', 'id' => $service->id]) }}" method="POST" onsubmit="return confirm('Delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Delete">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="kt_modal_edit_service_{{ $service->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'services', 'id' => $service->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Service</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Service Name</label>
                                        <input type="text" class="form-control form-control-solid" name="name" value="{{ $service->name }}" required />
                                    </div>
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Price (৳)</label>
                                            <input type="number" class="form-control form-control-solid" name="price" value="{{ $service->price }}" required />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="fs-6 fw-semibold mb-2">Duration</label>
                                            <input type="text" class="form-control form-control-solid" name="duration" value="{{ $service->duration }}" />
                                        </div>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Category</label>
                                        <select class="form-select form-select-solid" name="category">
                                            @foreach(['Hair', 'Skin', 'Nails', 'Massage', 'Other'] as $cat)
                                            <option value="{{ $cat }}" {{ $service->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Description</label>
                                        <textarea class="form-control form-control-solid" name="description" rows="3">{{ $service->description }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Image</label>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-3">
                                                <label class="d-block mb-2 text-muted">Current & Preview</label>
                                                <div class="image-input image-input-outline" style="background-image: url('{{ $service->image_url ?? 'https://placehold.co/150x150' }}')">
                                                    <img id="edit_preview_{{ $service->id }}" src="{{ $service->image_url ?? 'https://placehold.co/150x150' }}" class="image-input-wrapper w-125px h-125px" style="object-fit: cover;">
                                                </div>
                                            </div>
                                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'edit_preview_{{ $service->id }}')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer flex-center">
                                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} entries</div>
            <ul class="pagination">
                {{ $services->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="kt_modal_add_service" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'services']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Service</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Service Name</label>
                        <input type="text" class="form-control form-control-solid" name="name" required />
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Price (৳)</label>
                            <input type="number" class="form-control form-control-solid" name="price" required />
                        </div>
                        <div class="col-md-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Duration</label>
                            <input type="text" class="form-control form-control-solid" name="duration" placeholder="e.g. 60 min" />
                        </div>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Category</label>
                        <select class="form-select form-select-solid" name="category">
                            @foreach(['Hair', 'Skin', 'Nails', 'Massage', 'Other'] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Description</label>
                        <textarea class="form-control form-control-solid" name="description" rows="3"></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Image</label>
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <img id="add_preview" src="https://placehold.co/150x150" class="image-input-wrapper w-125px h-125px" style="object-fit: cover; border: 1px solid #eee; border-radius: 0.475rem;">
                            </div>
                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'add_preview')" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
