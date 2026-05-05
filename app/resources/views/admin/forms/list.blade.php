@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Forms</h3>
          <a href="{{ route('forms.create') }}" class="btn btn-info" style="float:right;margin-bottom:0px;">Add Form</a>
          <p class="mb-1">
            Manage all forms data here.
          </p>
        </div>

        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Form List</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Form Id</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Form Title</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Visibility</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Section</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Form Image</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($forms as $key => $form)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0 text-center">{{ $key + 1 }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $form->title }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">
                            @if($form->visibility)
                              <span class="badge bg-warning">Every One</span>
                            @else
                              <span class="badge bg-info">Individual</span>
                            @endif
                          </p>
                        </td>
                        <td class="wrap-text">
                          <!-- Button trigger modal -->
                          <a class="text-sm font-weight-normal mb-0 text-center" href="#" data-bs-toggle="modal"
                            data-bs-target="#descModal{{ $form->id }}">
                            Click here
                          </a>

                          <!-- Modal -->
                          <div class="modal fade" id="descModal{{ $form->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel{{ $form->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel{{ $form->id }}">Form Description
                                  </h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">X</button>
                                </div>
                                <div class="modal-body" style="width:100%;text-wrap: auto;">
                                  {!!  $form->description !!}
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="wrap-text">
                          @if($form->sections)
                            @foreach ($form->sections as $section)
                              <h6 class="mb-0 text-sm">{{ $section->name }}</h6>
                              <p class="text-xs text-secondary mb-0">(Step: {{ $section->step_number ?? "" }})</p>
                              <p class="text-xs text-secondary mb-0 wrap-text">(Description:{{ $section->description ?? "" }})
                              </p>
                              <hr>
                            @endforeach
                          @endif
                        </td>
                        <td>
                          @if($form->form_image)
                            <div class="mb-2">
                              <img src="{{ asset('storage/' . $form->form_image) }}" width="120">
                            </div>
                          @endif
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $form->created_at->format('d M Y h:i A') }}</p>
                        </td>
                        <td>
                          <div style="display: flex; gap: 8px;">
                            <a class="btn btn-info" href="{{ route('forms.edit', $form) }}">Edit</a>
                            <form action="{{ route('forms.destroy', $form) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this form?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">
                                Delete
                              </button>
                            </form>
                          </div>
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