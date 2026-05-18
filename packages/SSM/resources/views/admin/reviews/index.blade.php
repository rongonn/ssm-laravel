@extends('isotope::master')

@section('title', 'Product Reviews')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.reviews') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Reviews" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Customer</th>
                    <th class="min-w-150px">Product</th>
                    <th class="min-w-100px">Rating</th>
                    <th class="min-w-200px">Review</th>
                    <th class="min-w-100px">Status</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">{{ $review->name }}</span>
                            <span class="text-muted fs-7">{{ $review->mobile ?? 'No Mobile' }}, Age: {{ $review->age ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold">{{ $review->product->name ?? 'Unknown' }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill fs-6 {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        <div class="text-gray-600 fs-7 text-truncate mw-250px" title="{{ $review->review_text }}">
                            {{ $review->review_text }}
                        </div>
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge badge-light-success">Approved</span>
                        @else
                            <span class="badge badge-light-warning">Pending</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-icon btn-bg-light btn-sm me-1 {{ $review->is_approved ? 'btn-active-color-warning text-warning' : 'btn-active-color-success text-success' }}" title="{{ $review->is_approved ? 'Reject Review' : 'Approve Review' }}">
                                    <i class="bi {{ $review->is_approved ? 'bi-x-circle' : 'bi-check-circle' }} fs-3"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.delete', ['table' => 'product_reviews', 'id' => $review->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm text-danger">
                                    <i class="bi bi-trash fs-3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-10">No reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() ?? 0 }} entries</div>
            <ul class="pagination">
                {{ $reviews->links() }}
            </ul>
        </div>
    </div>
</div>
@endsection
