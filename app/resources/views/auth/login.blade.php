@extends('layouts.app')

@section('content')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{ asset('admin/assets/img/bg1.png') }}');">
      <span class="mask bg-gradient-dark-1 opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>                  
                </div>
              </div>
              <div class="card-body">
                @include('includes/errors/validation-errors')
                <form method="POST" action="{{ route('login') }}">
                  @csrf                  
                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Email or Mobile No</label>
                    <input type="text" name="email" class="form-control" required>
                  </div>
                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign in</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('includes/footer-text')

    </div>
</main>
@endsection
