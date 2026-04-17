@extends('isotope::master')

@section('title', 'Contact Messages')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route('admin.contacts') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Messages" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-150px">Sender</th>
                    <th class="min-w-200px">Subject</th>
                    <th class="min-w-300px">Message</th>
                    <th class="min-w-100px">Status</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @forelse($contacts as $contact)
                <tr class="{{ $contact->is_read ? 'opacity-75' : 'bg-light-primary rounded' }}">
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">{{ $contact->name }}</span>
                            <span class="text-muted fs-7">{{ $contact->email }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="text-gray-800 fw-bold">{{ $contact->subject ?? 'No Subject' }}</span>
                    </td>
                    <td>
                        <div class="text-gray-600 fs-7 text-truncate mw-300px" title="{{ $contact->message }}">
                            {{ $contact->message }}
                        </div>
                    </td>
                    <td>
                        @if($contact->is_read)
                            <span class="badge badge-light-success">Read</span>
                        @else
                            <span class="badge badge-light-warning">New</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            @if(!$contact->is_read)
                            <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Mark as Read">
                                    <i class="bi bi-check2-circle fs-3"></i>
                                </button>
                            </form>
                            @endif
                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modal_msg_{{ $contact->id }}" title="View Full Message">
                                <i class="bi bi-eye fs-3"></i>
                            </button>
                            <form action="{{ route('admin.delete', ['table' => 'contacts', 'id' => $contact->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                    <i class="bi bi-trash fs-3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Message View Modal -->
                <div class="modal fade" id="modal_msg_{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bold">Contact Message</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <i class="bi bi-x fs-1"></i>
                                </div>
                            </div>
                            <div class="modal-body py-10 px-lg-17">
                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-6 fw-semibold mb-2 text-muted">Sender Name</label>
                                        <div class="fs-5 fw-bold text-gray-800">{{ $contact->name }}</div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-6 fw-semibold mb-2 text-muted">Email Address</label>
                                        <div class="fs-5 fw-bold text-gray-800">{{ $contact->email }}</div>
                                    </div>
                                </div>
                                <div class="fv-row mb-8">
                                    <label class="fs-6 fw-semibold mb-2 text-muted">Subject</label>
                                    <div class="fs-5 fw-bold text-gray-800">{{ $contact->subject ?? 'No Subject' }}</div>
                                </div>
                                <div class="fv-row mb-8">
                                    <label class="fs-6 fw-semibold mb-2 text-muted">Message Content</label>
                                    <div class="fs-6 text-gray-700 bg-light p-6 rounded-3 border border-dashed border-gray-300">
                                        {{ $contact->message }}
                                    </div>
                                </div>
                                <div class="text-muted fs-7 text-end">Received at: {{ $contact->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="modal-footer flex-center">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                @if(!$contact->is_read)
                                <form action="{{ route('admin.contacts.read', $contact->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Mark as Read</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-10">No messages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() ?? 0 }} entries</div>
            <ul class="pagination">
                {{ $contacts->links() }}
            </ul>
        </div>
    </div>
</div>
@endsection
