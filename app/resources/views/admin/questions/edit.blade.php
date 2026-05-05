@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Edit Question</h3>
          <p class="mb-1">
            Edit question data.
          </p>
        </div>

        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          @include('includes/errors/session-message')
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <!-- <h6 class="mb-2">Edit User</h6> -->
              <form action="{{ route('questions.update', $question) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="input-group input-group-static mb-4">
                  <label>Question</label>
                  <input type="text" name="questions[question_text]" value="{{ $question->question_text }}"
                    class="form-control mb-2" placeholder="Question" required>
                  <input type="hidden" name="question_id" value="{{ $question->id }}">
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="input-group input-group-static mb-4">
                      <label for="options" class="ms-0">Select Options Type</label>
                      <select name="questions[type]" class="form-control mb-2" required>
                        <option value="radio" {{ $question->type === 'radio' ? "selected" : "" }}>Radio</option>
                        <option value="checkbox" {{ $question->type === 'checkbox' ? "selected" : "" }}>Checkbox</option>
                        <option value="text" {{ $question->type === 'text' ? "selected" : "" }}>Textbox</option>
                        <option value="textarea" {{ $question->type === 'textarea' ? "selected" : "" }}>Textarea</option>
                        <option value="email" {{ $question->type === 'email' ? "selected" : "" }}>Email</option>
                        <option value="number" {{ $question->type === 'number' ? "selected" : "" }}>Number</option>
                        <option value="url" {{ $question->type === 'url' ? "selected" : "" }}>Url</option>
                        <option value="tel" {{ $question->type === 'tel' ? "selected" : "" }}>Mobile</option>
                        <option value="range" {{ $question->type === 'range' ? "selected" : "" }}>Range</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <br>
                    <div class="input-group input-group-static mb-4">
                      <label for="is_required" class="ms-0">
                        <input type="checkbox" name="questions[is_required]" {{ $question->is_required === 1 ? "checked" : "" }}>
                        Question is Required
                      </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <br>
                    <div class="input-group input-group-static mb-4">
                      <label for="is_others" class="ms-0">
                        <input type="checkbox" name="questions[is_others]" {{ $question->is_others === 1 ? "checked" : "" }}>
                        Question has other option?
                      </label>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <br>
                    <div class="input-group input-group-static mb-4">
                      <label class="ms-0">
                        <input type="checkbox" class="toggle-image" {{ $question->has_image ? 'checked' : '' }}>
                        Question has image?
                      </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="range-wrapper {{ $question->type === 'range' ? '' : 'd-none' }}">
                      <div class="input-group input-group-static mb-4">
                        <label>Range Value</label>
                        <input type="number" name="questions[range_number]" class="form-control"
                          value="{{ $question->range_number ?? 5 }}" min="1" max="10">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="image-upload-wrapper {{ $question->has_image ? '' : 'd-none' }}">
                  @if($question->has_image)
                    <div class="mb-2">
                      <img src="{{ asset('storage/' . $question->has_image) }}" width="120">
                    </div>
                  @endif
                  <div class="input-group input-group-static mb-4">
                    <label>Upload Image</label>
                    <input type="file" name="q_image" class="form-control" accept="image/*">
                  </div>
                </div>

                @foreach ($question->options as $option)
                  @if($option)
                    <div class="options-block mb-2">
                      <div class="row">
                        <div class="col-md-11">
                          <div class="input-group input-group-static mb-4">
                            <label>Add Option</label>
                            <input type="text" name="options[{{ $option->id }}]" class="form-control"
                              value="{{ $option->option_text }}" required>
                          </div>
                        </div>
                        <div class="col-md-1">
                          <button type="button" class="btn btn-danger remove-option">-</button>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach

                <div class="row">
                  <div class="options-wrapper"></div>
                  <button type="button" class="btn btn-info add-option w-10">
                    + Add Option
                  </button>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn bg-gradient-dark w-30 my-4 mb-2">Submit</button>
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
  <script>
    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('add-option')) {
        let wrapper = e.target.previousElementSibling;
        let optionHTML = `<div class="options-block mb-2">
                                                <div class="row">
                                                  <div class="col-md-11">
                                                    <div class="input-group input-group-static mb-4">
                                                      <label>Add Option</label>
                                                      <input type="text" name="new_options[]" class="form-control" required>
                                                    </div>
                                                  </div>
                                                  <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger remove-option">-</button>
                                                  </div>
                                                </div>
                                              </div>`;
        wrapper.insertAdjacentHTML('beforeend', optionHTML);
      }

      if (e.target.classList.contains('remove-option')) {
        e.target.closest('.options-block').remove();
      }

      if (e.target.classList.contains('remove-question')) {
        e.target.closest('.question-block').remove();
      }
    });
    document.addEventListener('change', function (e) {
      if (e.target.name === 'questions[type]') {
        let type = e.target.value;
        let block = e.target.closest('form');
        let optionsWrapper = block.querySelector('.options-wrapper');
        let addOptionBtn = block.querySelector('.add-option');
        let rangeWrapper = block.querySelector('.range-wrapper');
        if (type === 'radio' || type === 'checkbox') {
          optionsWrapper.classList.remove('d-none');
          addOptionBtn.style.display = 'inline-block';
          rangeWrapper.classList.add('d-none');
        } else if (type === 'range') {
          optionsWrapper.classList.add('d-none');
          addOptionBtn.style.display = 'none';
          rangeWrapper.classList.remove('d-none');
        } else {
          optionsWrapper.classList.add('d-none');
          addOptionBtn.style.display = 'none';
          rangeWrapper.classList.add('d-none');
        }
      }
    });
    document.addEventListener('change', function (e) {
      if (e.target.classList.contains('toggle-image')) {
        let wrapper = document.querySelector('.image-upload-wrapper');
        if (e.target.checked) {
          wrapper.classList.remove('d-none');
        } else {
          wrapper.classList.add('d-none');
          let input = wrapper.querySelector('input[type="file"]');
          if (input) input.value = '';
        }
      }
    });
  </script>
@endsection