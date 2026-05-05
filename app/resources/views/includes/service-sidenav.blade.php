<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2 ps ps--active-y" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0 text-center" href="#" target="_blank">
        <!-- <img src="{{ asset('admin/assets/img/logo/clogo.png') }}" class="navbar-brand-img" alt="main_logo"> -->
        <span class="ms-1 text-lg text-dark" style="font-weight:bolder;color:#ee0979 !important;">Synapsis</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto h-auto ps" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item mb-2 mt-0">
          <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-dark collapsed" aria-controls="ProfileNav" role="button" aria-expanded="false">
            @if(Auth::user()->profile_pic)
              <img src="{{ asset('storage/profile/' . Auth::user()->profile_pic) }}" class="avatar" alt="{{ Auth::user()->name }}">
            @else
              <img src="{{ asset('admin/assets/img/logo/user.png' . Auth::user()->profile_pic) }}" class="avatar" alt="{{ Auth::user()->name }}">
            @endif
            <span class="nav-link-text ms-2 ps-1">{{ Auth::user()->name }}</span>
          </a>
          <div class="collapse" id="ProfileNav" style="">
            <ul class="nav ">
              <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('users.edit',Auth::user()) }}">
                  <span class="sidenav-mini-icon"> MP </span>
                  <span class="sidenav-normal  ms-3  ps-1"> My Profile </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-dark"
                          onclick="event.preventDefault();
                                          document.getElementById('logout-form2').submit();">
                  <span class="sidenav-mini-icon"> L </span>
                  <span class="sidenav-normal  ms-1  ps-1"> {{ __('Logout') }} </span>
                </a>
                <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        </li>
        <hr class="horizontal dark mt-0">
         <li class="nav-item">
          <a class="nav-link {{ Route::is('home') ? 'bg-gradient-dark active text-white' : 'text-dark' }}" href="{{ route('home') }}">
            <i class="material-symbols-rounded">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

         <hr class="horizontal dark mt-0">
         <li class="nav-item">
            <a class="nav-link {{ Route::is('service-calls.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}" href="{{ route('service-calls.index') }}">
            <i class="material-symbols-rounded">receipt_long</i>
              <span class="nav-link-text ms-1">Service Calls</span>
            </a>
          </li>   
      </ul>
  </aside>