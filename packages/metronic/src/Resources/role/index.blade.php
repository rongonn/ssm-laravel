@extends('isotope::master')

@section('title', 'Manage Roles')

@section('content')
<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <h3 class="card-label">User Roles</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route(tenant() ? 'authorization.roles.create' : 'owner.authorization.roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus fs-2"></i>Add Role
            </a>
        </div>
    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_roles_table">
            <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Title</th>
                    <th class="min-w-100px">Created Date</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
                @foreach ($roles as $role)
                <tr>
                    <td>
                        <span class="text-gray-800 fw-bold">{{ $role->title }}</span>
                    </td>
                    <td>{{ $role->created_at->format('d M Y, h:i a') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end flex-shrink-0">
                            @if ($role->id != 1)
                            <a href="{{ route(tenant() ? 'authorization.roles.edit' : 'owner.authorization.roles.edit', $role->id) }}" 
                               class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                               title="Edit">
                                <i class="bi bi-pencil fs-4"></i>
                            </a>
                            <form action="{{ route(tenant() ? 'authorization.roles.destroy' : 'owner.authorization.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Delete">
                                    <i class="bi bi-trash fs-4"></i>
                                </button>
                            </form>
                            @else
                            <span class="badge badge-light-secondary">Default</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection