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
    color: #FFFFFF !important;
  }

  .force-pathway-card {
    height: 100%;
    border: 1px solid #E7E2DA;
    border-radius: 12px;
    box-shadow: none;
    transition: border-color 0.2s ease, transform 0.2s ease;
  }

  .force-pathway-card:hover,
  .force-pathway-card:focus-within {
    border-color: #4297A0;
    transform: translateY(-1px);
  }

  .force-pathway-card h4 {
    color: #0F2C3E;
  }

  .force-pathway-card .sub-text {
    color: #51636f;
    line-height: 1.5;
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
          <div class="col-lg-10 col-md-10 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom force-auth-card">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="force-auth-heading py-3 pe-1">
                  <h4 class="font-weight-bolder text-center mt-2 mb-0" style="color:black;">Choose your FORCE Pathway</h4>                  
                </div>
              </div>
              <div class="card-body mb-4 mt-4">
                @include('includes/errors/validation-errors')


          <div class="row">
            <div class="col-lg-6 col-md-6 col-12 mx-auto">              
              <a href="{{route('force.course',1)}}"> 
                <div class="card z-index-0 fadeIn3 fadeInBottom force-pathway-card">
                  <div class="card-body">
                    <h4 style="text-align: center;">Student Career Profile Assessment</h4>
                      <p class="sub-text" style="text-align: center;">Discover your child’s interests, strengths, and career direction through a structured student profile assessment.</p>
                      <p class="sub-text" style="text-align: center;"><em>Best for students who want clarity before choosing a program or career pathway.</em></p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-lg-6 col-md-6 col-12 mx-auto">
              <a href="{{route('force.course',2)}}"> 
                <div class="card z-index-0 fadeIn3 fadeInBottom force-pathway-card">
                  <div class="card-body">
                    <h4 style="text-align: center;">Become a STEAM-X Scholar</h4><br>
                      <p class="sub-text" style="text-align: center;">A guided program for high-school students to explore 21st-century interdisciplinary careers, build real-world projects, and develop the confidence to make better career choices.</p>
                      <p class="sub-text" style="text-align: center;"><em>Best for students ready for deeper career exploration, projects, mentoring, and skill-building.</em></p>
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
