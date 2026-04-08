@extends('isotope::master')

@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Your Notifications</h2>
            <div>
                <form action="{{ route(tenant() ? 'notifications.markAllAsRead' : 'owner.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light-primary font-weight-bold btn-hover-primary">
                        <i class="fa-solid fa-envelope-open"></i>
                        Mark All as Read
                    </button>
                </form>
                <form action="{{ route(tenant() ? 'notifications.clearAll' : 'owner.notifications.clearAll') }}" method="POST" class="d-inline ml-2">
                    @csrf
                    <button type="submit" class="btn btn-light-danger font-weight-bold btn-hover-danger">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Clear All Notifications
                    </button>
                </form>
            </div>
        </div>
        <table class="table table-row-bordered">
            <tbody>
                @forelse ($notifications as $notification)
                    <tr class="align-middle {{ is_null($notification->read_at) ? 'bg-light-dark' : '' }}">
                        <td class="text-center">
                            <i class="{{ $notification->data['icon'] }}"></i>
                        </td>
                        <td>
                            <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">{{ $notification->data['title'] }}</a>
                            <div class="text-gray-500 fs-7 mt-1">{{ $notification->data['description'] }}</div>
                        </td>
                        <td class="text-end pe-5">
                            <span class="fs-8 d-block">{{ $notification->created_at->diffForHumans() }}</span>
                            @if(is_null($notification->read_at))
                                <form action="{{ route(tenant() ? 'notifications.markAsRead' : 'owner.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-link fs-8 btn-sm">
                                        <i class="fas fa-check mr-2"></i>
                                        Mark as Read
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center table-info" colspan="2">
                            NO NOTIFICATIONS.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        {!! $notifications->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
