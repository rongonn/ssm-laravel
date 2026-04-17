@extends('isotope::master')

@section('title', 'Manage Categories')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.categories') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Categories" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_category">
                <i class="bi bi-plus fs-2"></i>Add Category
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_categories_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Name</th>
                    <th class="min-w-100px">Slug</th>
                    <th class="min-w-100px">Status</th>
                    <th class="text-end min-w-70px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <span class="badge badge-light-{{ $category->is_active ? 'success' : 'danger' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_edit_category_{{ $category->id }}"
                                    title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'categories', 'id' => $category->id]) }}" method="POST" onsubmit="return confirm('Delete this category?')">
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
                <div class="modal fade" id="kt_modal_edit_category_{{ $category->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <form action="{{ route('admin.update', ['table' => 'categories', 'id' => $category->id]) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h2 class="fw-bold">Edit Category</h2>
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>
                                <div class="modal-body py-10 px-lg-17">
                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Category Name</label>
                                        <input type="text" class="form-control form-control-solid" name="name" value="{{ $category->name }}" required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Status</label>
                                        <select class="form-select form-select-solid" name="is_active">
                                            <option value="1" {{ $category->is_active ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactive</option>
                                        </select>
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
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} entries</div>
            <ul class="pagination">
                {{ $categories->links() }}
            </ul>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="kt_modal_add_category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('admin.store', ['table' => 'categories']) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2 class="fw-bold">Add Category</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Category Name</label>
                        <input type="text" class="form-control form-control-solid" name="name" required />
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
@endsection
