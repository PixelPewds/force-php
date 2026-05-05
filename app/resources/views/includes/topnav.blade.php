<!-- Navbar -->
<nav
  class="navbar navbar-main navbar-expand-lg position-sticky mt-2 top-1 px-0 py-1 mx-3 shadow-none border-radius-lg z-index-sticky"
  id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
      </ol>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <!-- <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div> -->
      </div>
      <ul class="navbar-nav d-flex align-items-center  justify-content-end">
        <li class="nav-item dropdown pe-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="material-symbols-rounded">notifications</i>
            @if(Auth::user()->notifications()->where('read_at', null)->count() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="font-size: 0.65rem;">
                {{ Auth::user()->notifications()->where('read_at', null)->count() }}
              </span>
            @endif
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton"
            style="min-width: 350px; max-height: 500px; overflow-y: auto;">
            @forelse(Auth::user()->notifications()->latest()->limit(10)->get() as $notification)
              <li class="mb-2">
                <a class="dropdown-item border-radius-md {{ $notification->isUnread() ? 'bg-light' : '' }}"
                  href="{{ $notification->action_url ?? 'javascript:;' }}"
                  onclick="markNotificationAsRead({{ $notification->id }})">
                  <div class="d-flex py-1">
                    <div class="d-flex flex-column justify-content-center flex-grow-1">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">{{ $notification->title }}</span>
                        @if($notification->isUnread())
                          <span class="badge badge-sm bg-info ms-2">New</span>
                        @endif
                      </h6>
                      <p class="text-xs text-secondary mb-1">
                        {{ Str::limit($notification->message, 60) }}
                      </p>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        {{ $notification->created_at->diffForHumans() }}
                      </p>
                    </div>
                  </div>
                </a>
              </li>
            @empty
              <li class="text-center py-3">
                <p class="text-secondary text-sm mb-0">No notifications</p>
              </li>
            @endforelse

            <!--@if(Auth::user()->notifications()->count() > 10)-->
            <!--  <li>-->
            <!--    <hr class="dropdown-divider">-->
            <!--  </li>-->
            <!--  <li class="text-center py-2">-->
            <!--    <a href="{{ route('notifications.index') }}" class="text-sm text-primary text-decoration-none">-->
            <!--      View all notifications-->
            <!--    </a>-->
            <!--  </li>-->
            <!--@endif-->
          </ul>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a id="navbarDropdown" class="nav-link text-body font-weight-bold px-0" href="#" role="button"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
          </a>
        </li>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link p-0 text-body" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->

<script>
  /**
   * Mark notification as read and redirect if action_url exists
   */
  function markNotificationAsRead(notificationId) {
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
          // Optionally reload to update notification count
          if (event.target.closest('a').href !== 'javascript:;') {
            // If there's an action URL, navigate after marking as read
            window.location.href = event.target.closest('a').href;
          } else {
            location.reload();
          }
        }
      })
      .catch(error => console.error('Error:', error));

    return false;
  }
</script>