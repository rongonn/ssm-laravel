@extends('isotope::master')

@section('title', 'Manage Gallery')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.gallery') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Gallery" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_gallery">
                <i class="bi bi-plus fs-2"></i>Add Image
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row g-6 g-xl-9">
            @foreach($gallery as $item)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card card-flush h-md-100">
                    <div class="card-header flex-nowrap pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-6 fw-bold text-gray-800 me-2">{{ $item->title }}</span>
                            <span class="text-gray-400 fw-semibold fs-7">{{ $item->category }}</span>
                        </div>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_edit_gallery_{{ $item->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'gallery', 'id' => $item->id]) }}" method="POST" onsubmit="return confirm('Remove from gallery?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-color-gray-400 btn-active-color-danger" title="Delete">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                        <div class="symbol symbol-150px mb-7">
                            <img src="{{ $item->image_url ?? 'https://placehold.co/150x150' }}" alt="image" style="object-fit: cover;" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="kt_modal_edit_gallery_{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <form action="{{ route('admin.update', ['table' => 'gallery', 'id' => $item->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h2 class="fw-bold">Edit Gallery Item</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="bi bi-x fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body py-10 px-lg-17">
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Title</label>
                                    <input type="text" class="form-control form-control-solid" name="title" value="{{ $item->title }}" required />
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Category</label>
                                    <select class="form-select form-select-solid" name="category">
                                        @foreach(['Hair', 'Skin', 'Nails', 'Interior', 'Events'] as $cat)
                                        <option value="{{ $cat }}" {{ $item->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2">Image</label>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3">
                                            <label class="d-block mb-2 text-muted">Current & Preview</label>
                                            <div class="image-input image-input-outline" style="background-image: url('{{ $item->image_url ?? 'https://placehold.co/150x150' }}')">
                                                <img id="edit_preview_{{ $item->id }}" src="{{ $item->image_url ?? 'https://placehold.co/150x150' }}" class="image-input-wrapper w-125px h-125px" style="object-fit: cover;">
                                            </div>
                                        </div>
                                        <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'edit_preview_{{ $item->id }}')" />
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
        </div>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $gallery->firstItem() }} to {{ $gallery->lastItem() }} of {{ $gallery->total() }} entries</div>
            <ul class="pagination">
                {{ $gallery->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="kt_modal_add_gallery" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'gallery']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Gallery Item</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Title</label>
                        <input type="text" class="form-control form-control-solid" name="title" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Category</label>
                        <select class="form-select form-select-solid" name="category">
                            @foreach(['Hair', 'Skin', 'Nails', 'Interior', 'Events'] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Image</label>
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <img id="add_preview" src="https://placehold.co/150x150" class="image-input-wrapper w-125px h-125px" style="object-fit: cover; border: 1px solid #eee; border-radius: 0.475rem;">
                            </div>
                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'add_preview')" required />
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
