@php
  $id = $id ?? 0;
@endphp
<aside
  class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2 ps ps--active-y"
  id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-dark position-absolute end-0 top-0 d-xl-none" aria-hidden="true"
      id="iconSidenav"></i>
    <a class="navbar-brand px-4 py-3 m-0 text-center" href="#" target="_blank">
      <img src="{{ asset('force/1.png') }}" class="navbar-brand-img" alt="main_logo">
      <span class="ms-1 text-lg text-dark" style="font-weight:bolder;color:#ee0979 !important;">Force</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto h-auto ps" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item mb-2 mt-0">
        <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-dark collapsed" aria-controls="ProfileNav"
          role="button" aria-expanded="false">
          @if(Auth::user()->profile_pic)
            <img src="{{ asset('storage/profile/' . Auth::user()->profile_pic) }}" class="avatar"
              alt="{{ Auth::user()->name }}">
          @else
            <img src="{{ asset('force/default.png') }}" class="avatar" alt="{{ Auth::user()->name }}"
              style="width: 41px;">
          @endif
          <span class="nav-link-text ms-2 ps-1">{{ Auth::user()->name }}</span>
        </a>
        <div class="collapse" id="ProfileNav" style="">
          <ul class="nav ">
            @hasanyrole('Super Admin')
            <li class="nav-item">
              <a class="nav-link text-dark" href="{{ route('users.edit', Auth::user()) }}">
                <span class="sidenav-mini-icon"> MP </span>
                <span class="sidenav-normal  ms-1  ps-1"> My Profile </span>
              </a>
            </li>
            @endhasanyrole

            @hasanyrole('Student')
            <li class="nav-item">
              <a class="nav-link text-dark" href="{{ route('studentprofile.edit', Auth::user()) }}">
                <span class="sidenav-mini-icon"> MP </span>
                <span class="sidenav-normal  ms-1  ps-1"> My Profile </span>
              </a>
            </li>
            @endhasanyrole

            <li class="nav-item">
              <a class="nav-link text-dark" onclick="event.preventDefault();
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
        <a class="nav-link {{ Route::is('home') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('home') }}">
          <i class="material-symbols-rounded">dashboard</i>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      @hasanyrole('Super Admin')
      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a data-bs-toggle="collapse" href="#teams"
          class="nav-link {{ Route::is('users.*') ? ' active text-dark' : 'text-dark collapsed' }}"
          aria-controls="dashboardsExamples" role="button"
          aria-expanded="{{ Route::is('users.*') ? 'true' : 'false' }}">
          <i class="material-symbols-rounded">group</i>
          <span class="nav-link-text ms-1 ps-1">Users</span>
        </a>
        <div class="{{ Route::is('users.*') ? 'collapse show' : 'collapse' }}" id="teams" style="">
          <ul class="nav ">
            <li class="nav-item">
              <a class="nav-link {{ Route::is('users.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('users.index') }}">
                <span class="sidenav-mini-icon"> U </span>
                <span class="sidenav-normal  ms-1  ps-1"> Registered Users </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('getStripeUrl') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('getStripeUrl') }}">
          <i class="material-symbols-rounded">money</i>
          <span class="nav-link-text ms-1">Stripe Pay URL</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('getStudentList') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('getStudentList') }}">
          <i class="material-symbols-rounded">group</i>
          <span class="nav-link-text ms-1">Student Enrollment</span>
        </a>
      </li>
      @endhasanyrole

      @hasanyrole('Super Admin')
      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a data-bs-toggle="collapse" href="#questons"
          class="nav-link {{ Route::is('forms.*', 'questions.*', 'getForms') ? ' active text-dark' : 'text-dark collapsed' }}"
          aria-controls="dashboardsExamples" role="button"
          aria-expanded="{{ Route::is('forms.*', 'questions.*', 'getForms') ? 'true' : 'false' }}">
          <i class="material-symbols-rounded">Assignment</i>
          <span class="nav-link-text ms-1 ps-1">Student Forms</span>
        </a>
        <div class="{{ Route::is('forms.*', 'questions.*', 'getForms') ? 'collapse show' : 'collapse' }}" id="questons"
          style="">
          <ul class="nav ">
            <li class="nav-item">
              <a class="nav-link {{ Route::is('forms.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('forms.index') }}">
                <span class="sidenav-mini-icon"> AF </span>
                <span class="sidenav-normal  ms-1  ps-1"> Forms </span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ Route::is('questions.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('questions.index') }}">
                <span class="sidenav-mini-icon"> AQ </span>
                <span class="sidenav-normal  ms-1  ps-1"> Questions </span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ Route::is('getForms') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('getForms') }}">
                <i class="material-symbols-rounded">assignment</i>
                <span class="nav-link-text ms-1">Assignment</span>
              </a>
            </li>

          </ul>
        </div>
      </li>
      @endhasanyrole

      @hasanyrole('Student')
      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a class="nav-link {{ Route::is('form-details') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('form-details') }}">
          <i class="material-symbols-rounded">assignment</i>
          <span class="nav-link-text ms-1">Student Assessment</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('task-completion.my-tasks') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('task-completion.my-tasks') }}">
          <i class="material-symbols-rounded">checklist</i>
          <span class="nav-link-text ms-1">My Tasks</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('resource.my-resources') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('resource.my-resources') }}">
          <i class="material-symbols-rounded">library_books</i>
          <span class="nav-link-text ms-1">Learning Resources</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ Route::is('schedule-calls.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('schedule-calls.index') }}">
          <i class="material-symbols-rounded">calendar_today</i>
          <span class="nav-link-text ms-1">Schedule Call</span>
        </a>
      </li>
      @endhasanyrole

      @hasanyrole('Super Admin')
      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a class="nav-link {{ Route::is('student.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('student.index') }}">
          <i class="material-symbols-rounded">group</i>
          <span class="nav-link-text ms-1">Student List</span>
        </a>
      </li>

      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a data-bs-toggle="collapse" href="#taskManagement"
          class="nav-link {{ Route::is('task-completion.*', 'task-completion.deadlines') ? 'active text-dark' : 'text-dark collapsed' }}"
          aria-controls="taskManagement" role="button"
          aria-expanded="{{ Route::is('task-completion.*', 'task-completion.deadlines') ? 'true' : 'false' }}">
          <i class="material-symbols-rounded">assignment_turned_in</i>
          <span class="nav-link-text ms-1 ps-1">Task Management</span>
        </a>
        <div class="{{ Route::is('task-completion.*', 'task-completion.deadlines') ? 'collapse show' : 'collapse' }}"
          id="taskManagement" style="">
          <ul class="nav ">
            <li class="nav-item">
              <a class="nav-link {{ Route::is('task-completion.index') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('task-completion.index') }}">
                <span class="sidenav-mini-icon"> TM </span>
                <span class="sidenav-normal  ms-1  ps-1"> All Assignments </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('task-completion.create') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('task-completion.create') }}">
                <span class="sidenav-mini-icon"> AA </span>
                <span class="sidenav-normal  ms-1  ps-1"> Assign Task </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('task-completion.deadlines') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('task-completion.deadlines') }}">
                <span class="sidenav-mini-icon"> OD </span>
                <span class="sidenav-normal  ms-1  ps-1"> Overdue Tasks </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a data-bs-toggle="collapse" href="#resourceManagement"
          class="nav-link {{ Route::is('resource.*', 'resource.deadlines') ? 'active text-dark' : 'text-dark collapsed' }}"
          aria-controls="resourceManagement" role="button"
          aria-expanded="{{ Route::is('resource.*', 'resource.deadlines') ? 'true' : 'false' }}">
          <i class="material-symbols-rounded">library_books</i>
          <span class="nav-link-text ms-1 ps-1">Resource Management</span>
        </a>
        <div class="{{ Route::is('resource.*', 'resource.deadlines') ? 'collapse show' : 'collapse' }}"
          id="resourceManagement" style="">
          <ul class="nav ">
            <li class="nav-item">
              <a class="nav-link {{ Route::is('resource.index') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('resource.index') }}">
                <span class="sidenav-mini-icon"> RM </span>
                <span class="sidenav-normal  ms-1  ps-1"> All Resources </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('resource.create') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('resource.create') }}">
                <span class="sidenav-mini-icon"> AR </span>
                <span class="sidenav-normal  ms-1  ps-1"> Add Resource </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Route::is('resource.deadlines') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
                href="{{ route('resource.deadlines') }}">
                <span class="sidenav-mini-icon"> RD </span>
                <span class="sidenav-normal  ms-1  ps-1"> Overdue Resources </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a class="nav-link {{ Route::is('schedule-calls.*') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('schedule-calls.index') }}">
          <i class="material-symbols-rounded">schedule</i>
          <span class="nav-link-text ms-1">Scheduled Calls</span>
        </a>
      </li>

      <hr class="horizontal dark mt-0">
      <li class="nav-item">
        <a class="nav-link {{ Route::is('users-activity') ? 'bg-gradient-dark active text-white' : 'text-dark' }}"
          href="{{ route('users-activity') }}">
          <i class="material-symbols-rounded">person</i>
          <span class="nav-link-text ms-1">User Acitivity Logs</span>
        </a>
      </li>
      @endhasanyrole


    </ul>
</aside>