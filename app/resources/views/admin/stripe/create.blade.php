@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Add\Update Stripe Payment URL</h3>
          <p class="mb-1">
            Add user.
          </p>
        </div>

        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <form action="{{ route('addStripeUrl') }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label>FORCE Career Exploration Payment URL</label>
                      <input name="exploration_pay" type="text" class="form-control" value="{{ $stripe->exploration_pay??null }}" required>
                      @error('exploration_pay')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label>FORCE Student-Career Assessment Payment URL</label>
                      <input name="assessment_pay" type="text" class="form-control" value="{{ $stripe->assessment_pay??null }}" autofocus="off"
                        required>
                        <input type="hidden" name="stripe_id" value="{{ $stripe->id??null }}">
                      @error('assessment_pay')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label><strong style="color:red;">Indian</strong> FORCE Career Exploration Payment URL</label>
                      <input name="exploration_pay_ind" type="text" class="form-control" value="{{ $stripe->exploration_pay_ind??null }}" required>
                      @error('exploration_pay_ind')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label><strong style="color:red;">Indian</strong> FORCE Student-Career Assessment Payment URL</label>
                      <input name="assessment_pay_ind" type="text" class="form-control" value="{{ $stripe->assessment_pay_ind??null }}" autofocus="off"
                        required>
                      @error('assessment_pay_ind')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                <!-- <div class="text-center"> -->
                <button type="submit" class="btn bg-gradient-dark w-30 my-4 mb-2">Submit</button>
                <!-- </div> -->
                 </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection