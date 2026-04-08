@extends('isotope::master')

@section('title', 'User Edit')

@push('buttons')
<a href="{{ route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index') }}" class="btn btn-sm btn-isotope fw-bold">List</a>
<button type="submit" form="form" class="btn btn-sm btn-isotope fw-bold ms-1">
    <i class="fas fa-paper-plane"></i>
    Save
</button>
@endpush

@section('content')
<div class="col-12 mb-2">
    <div class="card">
        <div class="card-body">
            <form action="{{ route(tenant() ? 'authorization.users.update' : 'owner.authorization.users.update', $user->id) }}" method="post" id="form">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <label for="">Name: </label>
                    <input name="name" type="text" class="form-control form-control-sm" placeholder="ex. Mr X" value="{{ $user->name }}">
                </div>
                <div class="mb-2">
                    <label for="">Email Address: </label>
                    <input name="email" type="email" class="form-control form-control-sm" placeholder="ex. example@test.com" value="{{ $user->email }}">
                </div>
                <div class="mb-2">
                    <label for="">Password: </label>
                    <input name="password" type="password" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label for="">Role: </label>
                    <select name="role" class="form-select form-select-sm" readonly>
                        <option value="">Choose...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if ($user->role_id == $role->id) selected @endif>{{ $role->title }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection