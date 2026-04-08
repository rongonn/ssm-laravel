@extends('isotope::master')

@section('title', 'Manage Users')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="bi bi-search position-absolute ms-4"></i>
                <form action="{{ route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index') }}" method="GET">
                    <input type="text" name="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Users" value="{{ request('search') }}" />
                </form>
            </div>
        </div>
        <div class="card-toolbar">
            <a href="{{ route(tenant() ? 'authorization.users.create' : 'owner.authorization.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus fs-2"></i>Add User
            </a>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">User</th>
                    <th class="min-w-100px">Role</th>
                    <th class="min-w-100px">Joined Date</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach($users as $user)
                <tr>
                    <td class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <a href="#">
                                <div class="symbol-label">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="{{ $user->name }}" class="w-100" />
                                </div>
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                            <span>{{ $user->email }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-light-success fw-bold">{{ $user->role->title }}</span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y, h:i a') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="{{ route(tenant() ? 'authorization.users.edit' : 'owner.authorization.users.edit', $user->id) }}" 
                               class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                               title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </a>
                            <form action="{{ route(tenant() ? 'authorization.users.destroy' : 'owner.authorization.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Delete">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex flex-stack flex-wrap pt-10">
            <div class="fs-6 fw-bold text-gray-700">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</div>
            <ul class="pagination">
                {{ $users->links() }}
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection