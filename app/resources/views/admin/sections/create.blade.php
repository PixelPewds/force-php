@extends('layouts.app')
@section('cascadingstyle')
  <link href="{{asset('admin/assets/quill/quill.snow.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('admin/assets/quill/atom-one-dark.min.css')}}" />
  <link rel="stylesheet" href="{{asset('admin/assets/quill/katex.min.css')}}" />
@endsection
@section('content')
  @include('includes/sidenav')
  <main class=" main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Create Form</h3>
          <p class="mb-1">Add new form</p>
        </div>
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <form action="{{ route('forms.store') }}" method="POST" id="myForm">
                @csrf
                <div class="row">
                  <div class="col-md-12">
                    <div class="input-group input-group-static mb-4">
                      <label>Form Title</label>
                      <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
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
                    <button class="btn bg-gradient-dark w-30">
                      Submit
                    </button>
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
    document.getElementById('myForm').addEventListener('submit', function (e) {
      const html = quill1.root.innerHTML;
      document.getElementById('formDescription').value = html;
    });
  </script>
@endsection