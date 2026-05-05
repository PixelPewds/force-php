@extends('layouts.app')

@section('content')
<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{ asset('admin/assets/img/bg1.png') }}');">
      <span class="mask bg-gradient-dark-1 opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-10 col-md-10 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-light shadow-dark border-radius-lg py-3 pe-1">
                  <h4 class="font-weight-bolder text-center mt-2 mb-0" style="color:black;">Register with Force</h4>                  
                </div>
              </div>
              <div class="card-body mb-4 mt-4">
                @include('includes/errors/validation-errors')


          <div class="row">
            <div class="col-lg-6 col-md-6 col-12 mx-auto">              
              <a href="{{route('force.course',1)}}"> 
                <div class="card z-index-0 fadeIn3 fadeInBottom" style="height:100%;    box-shadow: 0 1px 2px 0 rgb(126 211 69);
    border: 1px solid #d81818;">               
                  <div class="card-body">
                    <h4 style="text-align: center;">Apply to FORCE Student-Career Assessment</h4>
                      <p class="sub-text" style="text-align: center;">Join the Career Development Lab for high-school students.</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-lg-6 col-md-6 col-12 mx-auto">
              <a href="{{route('force.course',2)}}"> 
                <div class="card z-index-0 fadeIn3 fadeInBottom" style="height:100%;    box-shadow: 0 1px 2px 0 rgb(126 211 69);
    border: 1px solid #d81818;">               
                  <div class="card-body">
                    <h4 style="text-align: center;">Apply to FORCE Career Exploration</h4><br>
                      <p class="sub-text" style="text-align: center;">Join the Career Development Lab for high-school students.</p>
                  </div>
                </div>
              </a>
            </div>
          </div>

                <!-- <form method="POST" action="{{ route('register') }}">
                  @csrf   
                  
                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>

                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Email</label>
                    <input type="text" name="email" class="form-control" required>
                  </div>

                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Phone</label>
                    <input type="text" name="mobile" class="form-control" required>
                  </div>

                  <div class="input-group input-group-static my-3">
                    <label class="ms-0">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>

                   <div class="input-group input-group-static my-3">
                    <label class="ms-0">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                  </div>
                </form>  -->
                

                <p class="mt-4 text-sm text-center">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Sign in</a>
                </p>
              
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('includes/footer-text')

    </div>
</main>
@endsection