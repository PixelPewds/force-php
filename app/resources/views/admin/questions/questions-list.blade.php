@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Questions</h3>
          <p class="mb-1">
            Manage all questions data here.
          </p>
        </div>

        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Question List</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Forms Title</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Section Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Queston Text</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Options</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($section->questions)
                      @foreach ($section->questions as $key => $question)
                        <tr>
                          <td>
                            <p class="text-sm font-weight-normal mb-0 text-center">{{ $key + 1 }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $section->form->title }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $section->name }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $question->question_text }}</p>
                            <p class="text-xs text-secondary mb-0">(
                              @if($question->is_required === 1)
                                <span class="text-success">Required</span>
                              @else
                                <span class="text-danger">Optional</span>
                              @endif
                              )
                            </p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $question->type }}</p>
                          </td>
                          <td>
                            <ol>
                              @foreach ($question->options as $option)
                                @if($option->option_text)
                                  <li class="text-sm font-weight-normal mb-0">{{ $option->option_text }}</li>
                                @else
                                  ---
                                @endif
                              @endforeach
                            </ol>

                            @if($question->has_image)
                              <div class="mb-2">
                                <img src="{{ asset('storage/' . $question->has_image) }}" width="120">
                              </div>
                            @endif

                            @if($question->type === 'range' && $question->range_number)
                              {{ $question->range_number ?? 5 }}
                            @endif
                          </td>
                          <td>
                            <div style="display: flex; gap: 8px;">
                              <a class="btn btn-info" href="{{ route('questions.edit', $question) }}">Edit</a>
                              <form action="{{ route('questions.destroy', $question) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this form?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                  Delete
                                </button>
                              </form>
                            </div>
                          </td>
                      @endforeach
                    @endif
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