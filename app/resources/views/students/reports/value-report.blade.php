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
              <div class="bg-gradient-warning shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Values Report</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Values</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score(Max 15)</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Additional Values
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Achievement</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Achievement'] ?? 0 }}</p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{  $results['additional'] ?? "---" }}</p>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Environment</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Environment'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Creative</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Creative'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Financial Prosperity
                      </th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Financial Prosperity'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Health</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Health'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Humility</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Humility'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Independence</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Independence'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Interdependence</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Interdependence'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Objectivity</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Objectivity'] ?? 0}}</p>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Responsibility</th>
                      <td>
                        <p class="text-sm font-weight-normal mb-0">{{ $results['Responsibility'] ?? 0 }}</p>
                      </td>
                      <td></td>
                    </tr>
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