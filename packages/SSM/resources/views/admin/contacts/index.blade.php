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
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_contacts_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Sender</th>
                    <th class="min-w-200px">Message</th>
                    <th class="min-w-100px">Date</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($contacts as $contact)
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fw-bold">{{ $contact->name }}</span>
                            <span class="text-gray-400 fs-7">{{ $contact->email }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-gray-800 fw-bold mb-1">{{ $contact->subject }}</div>
                        <div class="text-gray-500 fs-7">{{ $contact->message }}</div>
                    </td>
                    <td>{{ $contact->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} entries</div>
            <ul class="pagination">
                {{ $contacts->links() }}
            </ul>
        </div>
    </div>
</div>
@endsection
