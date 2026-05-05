@extends('layouts.app')

@section('cascadingstyle')
<style>
  body,
  .main-content {
    font-family: 'IBM Plex Sans', Arial, sans-serif;
    background: #FDFDF9;
    color: #0F2C3E;
  }

  h1,
  h2,
  h3,
  h4 {
    font-family: 'Barlow', Arial, sans-serif;
  }

  .force-auth-page {
    background-color: #FDFDF9;
  }

  .force-auth-page .mask {
    display: none;
  }

  .force-auth-card {
    border: 1px solid #E7E2DA;
    border-radius: 12px;
    box-shadow: none;
    overflow: hidden;
  }

  .force-auth-heading {
    background: #0F2C3E;
    border-radius: 8px;
    box-shadow: none;
  }

  .force-auth-heading h4 {
    color: #FFFFFF;
  }

  .force-auth-card label {
    color: #4297A0;
  }

  .force-auth-card .btn {
    background: #FE646F !important;
    background-image: none !important;
    border-radius: 8px;
    box-shadow: none;
  }

  .force-auth-card .text-gradient {
    background-image: none;
    color: #4297A0 !important;
  }
</style>
@endsection

@section('content')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100 force-auth-page">
      <span class="mask bg-gradient-dark-1 opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom force-auth-card">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="force-auth-heading py-3 pe-1">
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
