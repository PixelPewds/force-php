@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Forms and Reports View</h3>
          <p class="mb-1">
            View Forms and Reports data here.
          </p>
        </div>
        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Forms and Reports View</h6>
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
                      @if($form->visibility === 1)
                        <tr>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $key + 1 }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $form->title }}</p>
                          </td>
                          <td>
                            @if($form->formsubmissions->first()?->status === 'submitted')
                              <div style="display: flex; gap: 8px;">
                                @if(str_contains($form->title, 'Interests') || str_contains($form->title, 'Interest'))
                                  <a class="btn btn-success"
                                    href="{{ route('interest-report.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}">View
                                    Report</a>

                                  <a class="btn btn-danger"
                                    href="{{ route('student.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}"
                                    onclick="return confirm('Are you sure you want to proceed further?');"> Enable
                                    for re-attempt</a>
                                @endif

                                @if(str_contains($form->title, 'Values') || str_contains($form->title, 'Value'))
                                  <a class="btn btn-success"
                                    href="{{ route('values-report.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}">View
                                    Report</a>

                                  <a class="btn btn-danger"
                                    href="{{ route('student.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}"
                                    onclick="return confirm('Are you sure you want to proceed further?');"> Enable
                                    for re-attempt</a>
                                @endif

                                @if(str_contains($form->title, 'Learnings') || str_contains($form->title, 'Learning'))
                                  <a class="btn btn-success"
                                    href="{{ route('learning-report.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}">View
                                    Report</a>

                                  <a class="btn btn-danger"
                                    href="{{ route('student.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}"
                                    onclick="return confirm('Are you sure you want to proceed further?');"> Enable
                                    for re-attempt</a>
                                @endif

                                @if(str_contains($form->title, 'Skills') || str_contains($form->title, 'Skill'))
                                  <a class="btn btn-success"
                                    href="{{ route('report.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}">View
                                    Report</a>

                                  <a class="btn btn-danger"
                                    href="{{ route('student.show', [$form->id, "studId" => $form->formsubmissions->first()?->student_id]) }}"
                                    onclick="return confirm('Are you sure you want to proceed further?');"> Enable
                                    for re-attempt</a>
                                @endif
                              </div>
                            @else
                              <a class="btn btn-danger" href="#">Not Completed Yet</a>
                            @endif
                          </td>
                        </tr>
                      @endif
                    @endforeach

                    @if (isset($submissions) && $count === 4)
                      <tr>
                        <td></td>
                        <td></td>
                        <td>
                          <div style="display:flex;gap:5px;">
                            <a class="btn btn-warning"
                              href="{{ route('student.create', ["formId" => $formData, "studId" => $submissions[0]->student_id, "view" => 1]) }}"
                              target="_blank">View
                              Report</a>
                            <a class="btn btn-info"
                              href="{{ route('student.create', ["formId" => $formData, "studId" => $submissions[0]->student_id]) }}">Download
                              Report</a>

                            <a class="btn btn-success" target="_blank"
                              href="{{ route('student.create', ["formId" => $formData, "studId" => $submissions[0]->student_id, 'final' => 1]) }}">Final
                              Report</a>
                          </div>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br><br>

      <!-- <div>
                                                                      <div class="calendly-inline-widget" data-url="https://calendly.com/kumbar48"
                                                                        style="min-width:320px;height:650px;">
                                                                      </div>

                                                                      <script src="https://assets.calendly.com/assets/external/widget.js" async></script>
                                                                    </div> -->
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