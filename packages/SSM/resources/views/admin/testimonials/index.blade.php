@extends('isotope::master')

@section('title', 'Manage Testimonials')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.testimonials') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Testimonials" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_testimonial">
                <i class="bi bi-plus fs-2"></i>Add Testimonial
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_testimonials_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Client</th>
                    <th class="min-w-200px">Feedback</th>
                    <th class="min-w-100px">Rating</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($testimonials as $testimonial)
                <tr>
                    <td class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <a href="#">
                                <div class="symbol-label">
                                    <img src="{{ $testimonial->avatar_url ?? 'https://placehold.co/50x50' }}" alt="{{ $testimonial->author }}" class="w-100" />
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $testimonial->author }}</a>
                        </div>
                    </td>
                    <td>{{ Str::limit($testimonial->content, 100) }}</td>
                    <td>
                        <div class="rating">
                            @for($i=1; $i<=5; $i++)
                            <div class="rating-label {{ $i <= $testimonial->rating ? 'checked' : '' }}">
                                <i class="bi bi-star-fill fs-6 {{ $i <= $testimonial->rating ? 'text-warning' : 'text-gray-400' }}"></i>
                            </div>
                            @endfor
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_edit_testimonial_{{ $testimonial->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'testimonials', 'id' => $testimonial->id]) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
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
                <div class="modal fade" id="kt_modal_edit_testimonial_{{ $testimonial->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'testimonials', 'id' => $testimonial->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Testimonial</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Client Name</label>
                                        <input type="text" class="form-control form-control-solid" name="author" value="{{ $testimonial->author }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Rating (1-5)</label>
                                        <input type="number" min="1" max="5" class="form-control form-control-solid" name="rating" value="{{ $testimonial->rating }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Feedback</label>
                                        <textarea class="form-control form-control-solid" name="content" rows="4" required>{{ $testimonial->content }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Avatar</label>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-3">
                                                <label class="d-block mb-2 text-muted">Current & Preview</label>
                                                <div class="image-input image-input-outline" style="background-image: url('{{ $testimonial->avatar_url ?? 'https://placehold.co/150x150' }}')">
                                                    <img id="edit_preview_{{ $testimonial->id }}" src="{{ $testimonial->avatar_url ?? 'https://placehold.co/150x150' }}" class="image-input-wrapper w-125px h-125px" style="object-fit: cover;">
                                                </div>
                                            </div>
                                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'edit_preview_{{ $testimonial->id }}')" />
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
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} entries</div>
            <ul class="pagination">
                {{ $testimonials->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="kt_modal_add_testimonial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'testimonials']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Testimonial</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Client Name</label>
                        <input type="text" class="form-control form-control-solid" name="author" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Rating (1-5)</label>
                        <input type="number" min="1" max="5" class="form-control form-control-solid" name="rating" value="5" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Feedback</label>
                        <textarea class="form-control form-control-solid" name="content" rows="4" required></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Avatar</label>
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
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
