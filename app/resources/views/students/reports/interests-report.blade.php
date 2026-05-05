@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Report View</h3>
          <p class="mb-1">
            Report data here.
          </p>
        </div>


        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Candidate Details</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-2">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Age</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Education</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($results)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['candidate'][1] ?? "---" }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{  $results['candidate'][0] ?? "---" }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{  $results['candidate'][2] ?? "---" }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{  $results['candidate'][3] ?? "---" }}</p>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-danger shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Interests Report</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-2">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Result </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score </th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($results)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">R Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableRTotal'] ?? 0 }}</p>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">A Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableATotal'] ?? 0 }}</p>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">I Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableITotal'] ?? 0 }}</p>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">S Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableSTotal'] ?? 0 }}</p>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">E Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableETotal'] ?? 0 }}</p>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">C Total</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $results['variableCTotal'] ?? 0 }}</p>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Interests Details</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-2">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Question</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Answere</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($results['interests'])
                      @foreach ($results['interests'] as $interest)
                        <tr>
                          <td>
                            <p class="text-sm font-weight-normal mb-0 wrap-text">{{ $interest['interestQuestion'] ?? "---" }}
                            </p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0 wrap-text">{{  $interest['interestAnswere'] ?? "---" }}
                            </p>
                          </td>
                        </tr>
                      @endforeach
                    @endif
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

  </script>
@endsection