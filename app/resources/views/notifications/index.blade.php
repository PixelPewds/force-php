@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">All Notifications</h6>
                        <div class="btn-group" role="group">
                            @if($notifications->total() > 0)
                                <a href="{{ route('notifications.mark-all-read') }}" method="POST"
                                    class="btn btn-sm btn-outline-secondary" onclick="event.preventDefault(); markAllAsRead();">
                                    <i class="material-symbols-rounded me-1" style="font-size: 1rem;">done_all</i>
                                    Mark All as Read
                                </a>
                                <a href="{{ route('notifications.delete-all') }}" method="DELETE"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirm('Are you sure you want to delete all notifications?') ? document.getElementById('deleteAllForm').submit() : false;">
                                    <i class="material-symbols-rounded me-1" style="font-size: 1rem;">delete</i>
                                    Delete All
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        @forelse($notifications as $notification)
                            <div
                                class="row mx-0 mb-2 p-3 {{ $notification->isUnread() ? 'bg-light border-left border-4 border-info' : '' }} border-radius-md">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="text-sm font-weight-bold mb-1">
                                                {{ $notification->title }}
                                                @if($notification->isUnread())
                                                    <span class="badge badge-sm bg-info ms-2">Unread</span>
                                                @endif
                                            </h6>
                                            <p class="text-sm text-secondary mb-2">
                                                {{ $notification->message }}
                                            </p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <small class="text-muted">
                                                    <i class="fa fa-clock me-1"></i>
                                                    {{ $notification->created_at->format('M d, Y \a\t h:i A') }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fa fa-tag me-1"></i>
                                                    <span
                                                        class="badge badge-sm bg-secondary">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($notification->action_url)
                                            <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary"
                                                onclick="markAsRead({{ $notification->id }})">
                                                <i class="material-symbols-rounded me-1"
                                                    style="font-size: 0.9rem;">arrow_forward</i>
                                                View
                                            </a>
                                        @endif

                                        @if($notification->isUnread())
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="markAsRead({{ $notification->id }})">
                                                <i class="material-symbols-rounded me-1" style="font-size: 0.9rem;">done</i>
                                                Mark as Read
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="markAsUnread({{ $notification->id }})">
                                                <i class="material-symbols-rounded me-1" style="font-size: 0.9rem;">mail</i>
                                                Mark as Unread
                                            </button>
                                        @endif

                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="deleteNotification({{ $notification->id }})">
                                            <i class="material-symbols-rounded me-1" style="font-size: 0.9rem;">delete</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="material-symbols-rounded" style="font-size: 3rem; color: #ccc;">notifications_none</i>
                                <p class="text-secondary mt-3">You have no notifications</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($notifications->total() > 0)
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <form id="deleteAllForm" action="{{ route('notifications.delete-all') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function markAsRead(notificationId) {
            const url = "{{ route('notifications.mark-read', ['notification' => ':id']) }}".replace(':id', notificationId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function markAsUnread(notificationId) {
            const url = "{{ route('notifications.mark-unread', ['notification' => ':id']) }}".replace(':id', notificationId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function deleteNotification(notificationId) {
            if (confirm('Are you sure you want to delete this notification?')) {
                const url = "{{ route('notifications.delete', ['notification' => ':id']) }}".replace(':id', notificationId);

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function markAllAsRead() {
            if (confirm('Are you sure you want to mark all notifications as read?')) {
                const url = "{{ route('notifications.mark-all-read') }}";

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
@endsection