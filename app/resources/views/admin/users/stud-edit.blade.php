@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Edit User</h3>
          <p class="mb-1">
            Edit user data.
          </p>
        </div>
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              @include('includes/errors/session-message')
              <!-- <h6 class="mb-2">Edit User</h6> -->
              <form action="{{ route('studentprofile.update', $user) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Full Name</label>
                      <input name="name" type="text" class="form-control" value="{{ $user->name }}" required>
                      @error('name')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Email</label>
                      <input name="email" type="email" class="form-control" value="{{ $user->email }}" autofocus="off"
                        required>
                      @error('email')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Phone Number</label>
                      <input name="mobile" type="text" class="form-control" value="{{ $user->mobile }}" required>
                      @error('mobile')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Password</label>
                      <input name="password" type="password" class="form-control" autofocus="off">
                      @error('password')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                   <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label for="gender" class="ms-0">Select the gender</label>
                      <select class="form-control" name="gender" id="gender" value="{{ $user->gender }}" required>
                        <option value="">&nbsp Select gender</option>
                        <option value="male" @selected($user->gender == 'male')>&nbsp Male</option>
                        <option value="female" @selected($user->gender == 'female')>&nbsp Female</option>
                      </select>
                      @error('gender')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label>Address</label>
                      <textarea name="address" class="form-control">{{ $user->address }}</textarea>
                      @error('address')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label for="profile_pic" class="ms-0">Profile Pic</label>
                      <input type="file" name="profile_pic" id="profile_pic">
                      @error('profile_photo')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Profile Pic &nbsp &nbsp</label>
                      @if($user->profile_pic)
                        <img src="{{ asset('storage/profile/' . $user->profile_pic) }}"
                          class="avatar avatar-sm me-3 border-radius-lg" alt="{{ $user->name }}">
                      @else
                        <img src="{{ asset('force/default.png') }}" class="avatar avatar-sm me-3 border-radius-lg"
                          alt="{{ $user->name }}" style="width:40px;">
                      @endif
                    </div>
                  </div>
                </div>


                <!-- <div class="text-center"> -->
                <button type="submit" class="btn bg-gradient-dark w-30 my-4 mb-2">Submit</button>
                <!-- </div> -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection