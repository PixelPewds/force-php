@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Student List</h3>          
          <p class="mb-1">
            Manage all Student here.
          </p>
        </div>

        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Student List</h6>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="table-responsive p-0">
                <table id="users-datatable" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Parent / Guardian</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contact</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">School / Grade</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Program</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($stripeUsers as $stripeUser)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->parent_name ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-1"><strong>Relation:</strong> {{ $stripeUser->relation ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-1"><strong>Education:</strong> {{ $stripeUser->education ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Comments:</strong> {{ $stripeUser->comments ?? 'N/A' }}</p>
                          <p class="text-xs text-danger mb-0"><strong>Program Type:</strong> {{ $stripeUser->type == 1 ? 'FORCE Student-Career Assessment' : 'FORCE Career Exploration' }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->student_name ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-1"><strong>Gender:</strong> {{ $stripeUser->gender ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Goals:</strong> {{ is_array($stripeUser->goals) ? implode(', ', $stripeUser->goals) : ($stripeUser->goals ?? 'N/A') }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->email ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Phone:</strong> {{ $stripeUser->phone ?? 'N/A' }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->city ?? 'N/A' }}, {{ $stripeUser->state ?? 'N/A' }} {{ $stripeUser->zip ?? '' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Address:</strong> {{ $stripeUser->address ?? 'N/A' }}{{ $stripeUser->address2 ? ' / ' . $stripeUser->address2 : '' }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->school ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-1"><strong>Board:</strong> {{ $stripeUser->board ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Grade:</strong> {{ $stripeUser->grade ?? 'N/A' }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-bold mb-1">{{ $stripeUser->program ?: 'Not specified' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Career clarity:</strong> {{ $stripeUser->career_clarity ?? 'N/A' }}</p>
                        </td>
                        <td>
                          <p class="text-sm text-success font-weight-bold mb-1 text-capitalize">{{ $stripeUser->payment_status ?? 'N/A' }}</p>
                          <p class="text-sm text-warning font-weight-bold mb-1 text-capitalize">{{ $stripeUser->client_reference_id ?? 'N/A' }}</p>
                          <p class="text-sm text-warning font-weight-bold mb-1 text-capitalize">{{ $stripeUser->stripe_session_id ?? 'N/A' }}</p>
                          <p class="text-xs text-secondary mb-0"><strong>Created:</strong> {{ optional($stripeUser->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</p>
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