@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Assessment View</h3>
          <p class="mb-1">
            Assessment data here.
          </p>
        </div>
        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Assessment View</h6>
              </div>
            </div>
            <div class="card-body pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Forms Title</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($forms as $key => $form)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $key + 1 }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $form->title }}</p>
                        </td>
                        <td>
                          @if($form->formSubmissions->first()?->status === 'submitted')
                            <div style="display: flex; gap: 8px;">
                              <h5 class="text-sm font-weight-bold">🎉 Assessment Completed</h5>
                            </div>
                          @else
                            <div style="display: flex; gap: 8px;">
                              <a class="btn btn-warning" target="_blank" href="{{ route('startForm', $form->id) }}">Complete
                                Assessment
                                Form</a>
                            </div>
                          @endif
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