@extends('layouts.app')

@section('cascadingstyle')
  <link href="{{asset('admin/assets/quill/quill.snow.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('admin/assets/quill/atom-one-dark.min.css')}}" />
  <link rel="stylesheet" href="{{asset('admin/assets/quill/katex.min.css')}}" />
@endsection

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Edit Form</h3>
          <p class="mb-1">
            Edit Form data.
          </p>
        </div>
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <!-- <h6 class="mb-2">Edit User</h6> -->
              <form action="{{ route('forms.update', $form) }}" method="post" id="myForm">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label>Full Name</label>
                      <input name="title" type="text" class="form-control" value="{{ $form->title }}" required>
                      @error('title')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-section">
                      <label class="form-label">Form Description</label>
                      @include('editor.editor')
                      <input type="hidden" name="description" id="formDescription">
                      <div id="description" style="height: 200px;" class="mb-3"></div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <hr>
                    <h5>Sections</h5>
                    <div id="sections-wrapper">
                      @foreach($form->sections as $index => $section)
                        <div class="section-item border p-3 mb-3">
                          <input type="hidden" name="sections[{{$index}}][id]" value="{{$section->id}}">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="input-group input-group-static mb-4">
                                <label>Step Number</label>
                                <input type="number" name="sections[{{$index}}][step_number]" class="form-control"
                                  value="{{$section->step_number}}" required>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="input-group input-group-static mb-4">
                                <label>Section Name</label>
                                <input type="text" name="sections[{{$index}}][name]" class="form-control"
                                  value="{{$section->name}}" required>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-12">
                              <label>Description</label>
                              <div class="form-section">
                                <input type="hidden" name="sections[{{$index}}][description]"
                                  class="section-description-input">
                                <div id="section-editor-{{$index}}" class="section-editor mb-3" style="height:150px;">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-section">-</button>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    <button type="button" class="btn btn-success" id="add-section">
                      + Add Section
                    </button>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn bg-gradient-dark w-30">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('javascript')
  <script src="{{asset('admin/assets/quill/highlight.min.js')}}"></script>
  <script src="{{asset('admin/assets/quill/quill.js')}}"></script>
  <script src="{{asset('admin/assets/quill/katex.min.js')}}"></script>
  <script>
    const description = {
      modules: {
        syntax: true,
        toolbar: true,
      },
      placeholder: 'Form Description...',
      theme: 'snow'
    };
    const quill1 = new Quill('#description', description);
    const existingContent = `{!! $form->description ?? 'Form Description...' !!}`;


    quill1.root.innerHTML = existingContent;
    document.getElementById('myForm').addEventListener('submit', function (e) {
      const html = quill1.root.innerHTML;
      document.getElementById('formDescription').value = html;
    });

    document.getElementById('myForm').addEventListener('submit', function () {
      document.querySelectorAll('.section-editor').forEach((editor, index) => {
        let html = editor.querySelector('.ql-editor').innerHTML;
        document.querySelectorAll('.section-description-input')[index].value = html;
      });
    });
  </script>

  <script>
    let sectionIndex = 0;
    document.getElementById('add-section').addEventListener('click', function () {
      let wrapper = document.getElementById('sections-wrapper');
      let html = `<div class="section-item border p-3 mb-3">
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                          <div class="input-group input-group-static mb-4">
                                                            <label>Step Number</label>
                                                            <input type="number" name="sections[${sectionIndex}][step_number]" class="form-control" required>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                          <div class="input-group input-group-static mb-4">
                                                            <label>Section Name</label>
                                                            <input type="text" name="sections[${sectionIndex}][name]" class="form-control" required>
                                                          </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                          <label class="form-label">Section Description</label>
                                                          <input type="hidden"
                                                                  name="sections[${sectionIndex}][description]"
                                                                  class="section-description-input">
                                                          <div id="section-editor-${sectionIndex}"
                                                                class="section-editor mb-3"
                                                                style="height:150px;"></div>
                                                        </div>
                                                        <div class="col-md-1">
                                                          <button type="button" class="btn btn-danger remove-section">-</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    `;
      wrapper.insertAdjacentHTML('beforeend', html);
      sectionIndex++;
      setTimeout(() => {
        initSectionEditors();
      }, 100);
    });
  </script>

  <script>


    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-section')) {
        e.target.closest('.section-item').remove();
      }
    });

    let sectionEditors = [];

    function initSectionEditors() {
      document.querySelectorAll('.section-editor').forEach((editor, index) => {
        if (editor.classList.contains('ql-container')) return;
        let quill = new Quill(editor, {
          theme: 'snow',
          modules: {
            toolbar: true
          }
        });
        sectionEditors.push(quill);
      });
    }
    initSectionEditors();

  </script>
@endsection