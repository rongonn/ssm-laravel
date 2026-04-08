@extends('isotope::master')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h1>Authorization Module</h1>
            <p>Welcome to the Authorization Module. Here you can manage user permissions and roles.</p>
            <a href="{{ route(tenant() ? 'authorization.users.index' : 'owner.authorization.users.index') }}" class="btn btn-primary">Go to</a>
        </div>
    </div>
</div>
@endsection