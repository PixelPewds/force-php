@extends('layouts.app')
@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0 mb-3">
          <h3 class="mb-0 h4 font-weight-bolder">Add Questions and Options</h3>
          <p class="mb-1">
            Add user.
          </p>
        </div>
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body">
              @include('includes/errors/validation-errors')
              <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group input-group-static mb-4">
                      <label for="form_id" class="ms-0">Form Title</label>
                      <input type="text" class="form-control mb-2" value="{{ $form->title }}" readonly>
                      <input type="hidden" name="form_id" id="form_id" class="form-control mb-2" value="{{ $form->id }}">
                      @error('form_id')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group input-group-static mb-4">
                      <label for="section_id" class="ms-0">Section question belongs to</label>
                      <input type="text" class="form-control mb-2" value="{{ $section->name }}" readonly>
                      <input type="hidden" name="section_id" id="section_id" class="form-control mb-2"
                        value="{{  $section->id }}">
                      @error('section_id')
                        <div class="mt-error">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div id="questions-wrapper"></div>
                <button type="button" id="add-question" class="btn btn-success">
                  + Add Question
                </button>
                <br><br>
                <button class="btn bg-gradient-dark w-30">Submit</button>
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
    let questionIndex = 0;
    document.getElementById('add-question').onclick = function () {
      let html = `<div class="question-block border p-3 mb-3" data-index="${questionIndex}">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Question</label>
                                            <input type="text" name="questions[${questionIndex}][question_text]" class="form-control mb-2" placeholder="Question" required>
                                        </div>                       

                                        <div class="row">
                                          <div class="col-md-3">
                                            <div class="input-group input-group-static mb-4">
                                              <label for="options" class="ms-0">Select Options Type</label>
                                              <select name="questions[${questionIndex}][type]" class="form-control mb-2" required>
                                                <option value="radio">Radio</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="text">Textbox</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="email">Email</option>
                                                <option value="number">Number</option>
                                                <option value="url">Url</option>
                                                <option value="tel">Mobile</option>
                                                <option value="range">Range</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="col-md-3">
                                            <br>
                                            <div class="input-group input-group-static mb-4">
                                                <label for="is_required" class="ms-0">
                                                  <input type="checkbox" name="questions[${questionIndex}][is_required]">
                                                  Question is Required
                                                </label>
                                            </div>
                                          </div>

                                          <div class="col-md-3">
                                            <br>
                                            <div class="input-group input-group-static mb-4">
                                                <label for="is_others" class="ms-0">
                                                  <input type="checkbox" name="questions[${questionIndex}][is_others]">
                                                  Question has other option?
                                                </label>
                                            </div>
                                          </div>
                                          <div class="col-md-3">
                                            <br>
                                            <div class="input-group input-group-static mb-4">
                                              <label class="ms-0">
                                                <input type="checkbox" class="toggle-image">
                                                Question has image?
                                              </label>
                                            </div>
                                          </div>
                                        </div>

                                        <div class="options-wrapper"></div>

                                        <div class="col-md-4">
                                          <div class="range-wrapper d-none mt-3">
                                            <div class="input-group input-group-static mb-4">
                                              <label>Range Value (Default 5)</label>
                                              <input type="number" 
                                                    name="questions[${questionIndex}][range_number]" 
                                                    class="form-control" 
                                                    value="5" min="1" max="10">
                                            </div>
                                          </div>
                                        </div>

                                        <button type="button" class="btn btn-info add-option">
                                          + Add Option
                                        </button>
                                        <button type="button" class="btn btn-danger remove-question">
                                          Delete Question
                                        </button>
                                      </div>
                                      `;
      document.getElementById('questions-wrapper').insertAdjacentHTML('beforeend', html);
      questionIndex++;
    };

    document.addEventListener('click', function (e) {
      if (e.target.classList.contains('add-option')) {
        let questionBlock = e.target.closest('.question-block');
        let wrapper = questionBlock.querySelector('.options-wrapper');
        let qIndex = questionBlock.getAttribute('data-index');
        let optionHTML = `
                            <div class="options-block mb-2">
                              <div class="row">
                                <div class="col-md-11">
                                  <div class="input-group input-group-static mb-4">
                                    <label>Add Option</label>
                                    <input type="text" 
                                            name="questions[${qIndex}][options][]" 
                                            class="form-control" required>
                                  </div>
                                </div>
                                <div class="col-md-1">
                                  <button type="button" class="btn btn-danger remove-option">-</button>
                                </div>
                              </div>
                            </div>
                          `;

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
      if (e.target.name.includes('[type]')) {
        let block = e.target.closest('.question-block');
        let type = e.target.value;
        let addOptionBtn = block.querySelector('.add-option');
        let optionsWrapper = block.querySelector('.options-wrapper');
        let rangeWrapper = block.querySelector('.range-wrapper');

        if (type === 'radio' || type === 'checkbox') {
          addOptionBtn.style.display = 'inline-block';
          optionsWrapper.classList.remove('d-none');
          rangeWrapper.classList.add('d-none');
        }

        else if (type === 'range') {
          addOptionBtn.style.display = 'none';
          optionsWrapper.innerHTML = ''; // clear old options
          optionsWrapper.classList.add('d-none');
          rangeWrapper.classList.remove('d-none');
        } else {
          addOptionBtn.style.display = 'none';
          optionsWrapper.innerHTML = '';
          optionsWrapper.classList.add('d-none');
          rangeWrapper.classList.add('d-none');
        }
      }
    });

    document.addEventListener('change', function (e) {
      if (e.target.classList.contains('toggle-image')) {
        let block = e.target.closest('.question-block');
        let existing = block.querySelector('.image-upload-wrapper');
        if (e.target.checked) {
          if (!existing) {
            let qIndex = block.getAttribute('data-index');
            let html = `
                            <div class="image-upload-wrapper mt-3">
                              <div class="input-group input-group-static mb-4">
                                <label>Upload Image</label>
                                <input type="file" 
                                       name="questions[${qIndex}][q_image]" 
                                       class="form-control" 
                                       accept="image/*" 
                                       required>
                              </div>
                            </div>
                          `;
            block.insertAdjacentHTML('beforeend', html);
          }
        } else {
          if (existing) {
            existing.remove();
          }
        }
      }
    });
  </script>
@endsection