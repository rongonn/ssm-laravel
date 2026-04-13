@extends('isotope::master')

@section('title', 'Manage Team')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.team') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Team" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_team">
                <i class="bi bi-plus fs-2"></i>Add Staff
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_team_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Member</th>
                    <th class="min-w-100px">Role</th>
                    <th class="min-w-150px">Specialty</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($team as $member)
                <tr>
                    <td class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <a href="#">
                                <div class="symbol-label">
                                    <img src="{{ $member->image_url ?? 'https://placehold.co/50x50' }}" alt="{{ $member->name }}" class="w-100" />
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $member->name }}</a>
                            <span class="text-gray-400 fw-bold fs-7">{{ Str::limit($member->bio, 50) }}</span>
                        </div>
                    </td>
                    <td>{{ $member->role }}</td>
                    <td>
                        @if(is_array($member->specialty))
                            @foreach($member->specialty as $s)
                                <span class="badge badge-light-primary">{{ $s }}</span>
                            @endforeach
                        @else
                            {{ $member->specialty }}
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_edit_team_{{ $member->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'team', 'id' => $member->id]) }}" method="POST" onsubmit="return confirm('Remove this member?')">
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
                <div class="modal fade" id="kt_modal_edit_team_{{ $member->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'team', 'id' => $member->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Team Member</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Full Name</label>
                                        <input type="text" class="form-control form-control-solid" name="name" value="{{ $member->name }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Role</label>
                                        <input type="text" class="form-control form-control-solid" name="role" value="{{ $member->role }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Specialties (comma separated)</label>
                                        <input type="text" class="form-control form-control-solid" name="specialty" value="{{ is_array($member->specialty) ? implode(', ', $member->specialty) : $member->specialty }}" />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Bio</label>
                                        <textarea class="form-control form-control-solid" name="bio" rows="3">{{ $member->bio }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Photo</label>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-3">
                                                <label class="d-block mb-2 text-muted">Current & Preview</label>
                                                <div class="image-input image-input-outline" style="background-image: url('{{ $member->image_url ?? 'https://placehold.co/150x150' }}')">
                                                    <img id="edit_preview_{{ $member->id }}" src="{{ $member->image_url ?? 'https://placehold.co/150x150' }}" class="image-input-wrapper w-125px h-125px" style="object-fit: cover;">
                                                </div>
                                            </div>
                                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*" onchange="previewImage(this, 'edit_preview_{{ $member->id }}')" />
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
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $team->firstItem() }} to {{ $team->lastItem() }} of {{ $team->total() }} entries</div>
            <ul class="pagination">
                {{ $team->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="kt_modal_add_team" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'team']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Staff</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Full Name</label>
                        <input type="text" class="form-control form-control-solid" name="name" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Role</label>
                        <input type="text" class="form-control form-control-solid" name="role" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Specialties (comma separated)</label>
                        <input type="text" class="form-control form-control-solid" name="specialty" placeholder="e.g. Hair Styling, Coloring" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Bio</label>
                        <textarea class="form-control form-control-solid" name="bio" rows="3"></textarea>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Photo</label>
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
