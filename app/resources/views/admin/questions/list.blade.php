@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Forms and Questions List</h3>
          <p class="mb-1">
            Manage all Forms and Questions data here.
          </p>
        </div>

        <div class="col-12">
          @include('includes/errors/session-message')
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Forms and Questions List</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table table-bordered align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Forms Title</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sections</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($forms as $key => $form)
                      @php $sectionCount = $form->sections->count(); @endphp

                      @foreach($form->sections as $index => $section)
                        <tr>
                          {{-- Form ID --}}
                          @if($index == 0)
                            <td rowspan="{{ $sectionCount }}" class="align-middle">
                              <p class="text-sm font-weight-normal mb-0">{{ $key + 1 }}</p>
                            </td>

                            {{-- Form Title --}}
                            <td rowspan="{{ $sectionCount }}" class="align-middle">
                              <p class="text-sm font-weight-bold mb-0">{{ $form->title }}</p>
                            </td>
                          @endif

                          {{-- Section Name --}}
                          <td>
                            <p class="text-sm font-weight-normal mb-0 wrap-text">{{ $section->name }}</p>
                          </td>

                          {{-- Section Description --}}
                          <td class="wrap-text">
                            <p class="text-sm font-weight-normal mb-0 wrap-text">
                              {{ $section->description }}
                            </p>
                          </td>

                          {{-- Actions --}}
                          <td>
                            <div style="display: flex; gap: 8px;">
                              <a class="btn btn-info" href="{{ route('questions.create', ['section_id' => $section->id]) }}">
                                Add Questions
                              </a>

                              <a class="btn btn-warning" href="{{ route('questions.show', $section->id) }}">
                                View Questions
                              </a>
                            </div>
                          </td>
                        </tr>
                      @endforeach
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