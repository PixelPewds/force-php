@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Add New User</h3>
          <p class="mb-1">
            Add user.
          </p>
        </div>

        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Full Name</label>
                      <input name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                      @error('name')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Email</label>
                      <input name="email" type="email" class="form-control" value="{{ old('email') }}" autofocus="off"
                        required>
                      @error('email')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label>Phone Number</label>
                      <input name="mobile" type="text" class="form-control" value="{{ old('mobile') }}" required>
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
                      <input name="password" type="password" class="form-control" autofocus="off" required>
                      @error('password')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label for="role_id" class="ms-0">Select the role</label>
                      <select class="form-control" name="role_id" id="role_id" value="{{ old('role_id') }}" required>
                        <option value="">&nbsp Select role</option>
                        @foreach($roles as $role)
                          <option value="{{$role}}">&nbsp;
                            {{$role}}
                          </option>
                        @endforeach
                      </select>
                      @error('role_id')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="input-group input-group-static mb-4">
                      <label for="gender" class="ms-0">Select the gender</label>
                      <select class="form-control" name="gender" id="gender" value="{{ old('gender') }}" required>
                        <option value="">&nbsp Select gender</option>
                        <option value="male">&nbsp Male</option>
                        <option value="female">&nbsp Female</option>
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
                      <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                      @error('address')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group input-group-static mb-4">
                      <label for="profile_pic" class="ms-0">Profile Pic &nbsp</label>
                      <br><br>
                      <input type="file" name="profile_pic">
                      @error('profile_pic')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
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