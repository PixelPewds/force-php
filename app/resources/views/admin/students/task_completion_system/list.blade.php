@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Users</h3>
          <a href="{{ route('users.create') }}" class="btn btn-info" style="float:right;margin-bottom:0px;">Add User</a>
          <p class="mb-1">
            Manage all users here.
          </p>
        </div>

        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Users List</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mobile</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            @if($user->profile_pic)
                              <img src="{{ asset('storage/profile/' . $user->profile_pic) }}"
                                class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $user->name }}">
                            @else
                              <img src="{{ asset('force/default.png') }}" class="avatar avatar-sm me-3 border-radius-lg"
                                alt="{{ $user->name }}" style="width: 65px !important;height: 45px !important;">
                            @endif
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                              <p class="text-xs text-secondary mb-0">({{ $user->getRoleNames()[0] ?? "" }})</p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $user->email }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $user->mobile }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $user->created_at->format('d M Y h:i A') }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $user->address }}</p>
                        </td>
                        <td>
                          <div style="display: flex; gap: 8px;">
                            <a class="btn btn-info" href="{{ route('users.edit', $user) }}">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">
                                Delete
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br><br>
      @include('includes/footer-text')
    </div>
  </main>
@endsection

@section('javascript')
  <script>
    var table = new DataTable('#users-datatable', {
      scrollX: false,
      "lengthChange": false,
      layout: {
        topStart: {
          // buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
          buttons: ['excel', 'pdf']
        }
      }
    });
    table.order([]).draw();
  </script>
@endsection