@extends('layouts.app')
@section('cascadingstyle')
  <style>
    body {
      background: #f1f3f4;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      max-width: 990px;;
      margin: auto;
    }

    .form-header {
      background: #673ab7;
      color: white;
      padding: 30px;
      border-radius: 12px;
      margin-bottom: 25px;
    }

    .form-header-image {
      border-radius: 12px;
      margin-bottom: 10px;
    }

    .section-card {
      display: none;
    }

    .section-card.active {
      display: block;
    }

    .question-card {
      border: none;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
      border-radius: 12px;
      margin-bottom: 20px;
    }

    .option-box {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all .2s;
    }

    .option-box:hover {
      background: #f8f9fa;
      border-color: #673ab7;
    }

    .option-selected {
      background: #ede7f6;
      border-color: #673ab7;
    }

    .progress {
      height: 8px;
      margin-bottom: 20px;
    }
     .interest-question {
      display: none;
    }

    .interest-question.active {
      display: block;
    }
  </style>
@endsection
@section('content')
  @php
    $savedAnswers = [];
  @endphp
  <div class="container py-4">
    <div class="form-container">

      @if($form->form_image)
        <div class="form-header-image">
          <img src="{{ asset('storage/' . $form->form_image) }}" alt="{{$form->title}}" style="width: 100%;height: 200px;border-radius: 7px;">
        </div>
      @endif

      <div class="form-header">
        <h3>{{$form->title}}</h3>
        <div>{!! $form->description !!}</div>
      </div>
      <div class="progress">
        <div class="progress-bar bg-success" id="progressBar" style="width:0%"></div>
      </div>
      <form id="assessmentForm">
        @csrf
        <input type="hidden" name="form_id" value="{{ $form->id }}">            
        @foreach($form->sections as $index => $section)
          <input type="hidden" name="current_step" value="{{ $section->step_number }}">
          <div class="section-card {{$index == 0 ? 'active' : ''}}" data-step="{{$index}}">
            <div class="card question-card">
              <div class="card-body">
                <h4 class="mb-3">{{$section->name}}</h4>
                <div class="text-muted mb-4">
                  {!! $section->description !!}
                </div>

                @if((str_contains($form->title, 'Interests') || str_contains($form->title, 'Interest')) && $section->step_number === 2)
              <div class="interest-questions">
                @foreach($section->questions as $qIndex => $question)
                  <div class="interest-question {{$qIndex == 0 ? 'active' : ''}}" data-q="{{$qIndex}}">

                    <label class="fw-bold mb-2">
                    {{$question->question_text}}
                    @if($question->is_required)
                      <span class="text-danger">*</span>
                    @endif
                  </label>
                    @if($question->type == 'text')
                      @if($question->has_image)
                        <div class="mb-2">
                          <img src="{{ asset('storage/' . $question->has_image) }}" width="500">
                        </div>
                      @endif
                      <div class="input-group input-group-static mb-4">
                        
                        <input type="text" class="form-control" name="answers[{{$question->id}}]" 
                        {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"
                        >
                      </div>
                    @endif

                      @if($question->type == 'textarea')
                        @if($question->has_image)
                          <div class="mb-2">
                            <img src="{{ asset('storage/' . $question->has_image) }}" width="500">
                          </div>
                        @endif
                        <div class="input-group input-group-static mb-4">
                          
                          <textarea class="form-control" rows="3" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}>{{ $savedAnswers[$question->id] ?? '' }}</textarea>
                        </div>
                      @endif
                    
                  </div>
                @endforeach
              </div>

              <div class="d-flex justify-content-between mt-3">
                <button type="button" id="prevQuestion" class="btn btn-secondary"><</button>
                <button type="button" id="nextQuestion" class="btn btn-primary">></button>
              </div>
        @else

                @foreach($section->questions as $question)
                  <div class="mb-4">
                    <label class="fw-bold mb-2">
                      {{$question->question_text}}
                      @if($question->is_required)
                        <span class="text-danger">*</span>
                      @endif
                    </label>

                    @if($question->type == 'radio')
                      @foreach($question->options as $key => $option)
                        <div class="option-box">
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{$question->id}}]" value="{{$option->id}}"
                              {{$question->is_required ? 'required' : ''}} id="radioButton{{ $question->id }}{{ $key }}"
                              {{ isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $option->id ? 'checked' : '' }}
                            >
                            <label class="custom-control-label" for="radioButton{{ $question->id }}{{ $key }}">
                              {{$option->option_text}}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    @endif

                    @if($question->type == 'range')
                      @php
                        $range = $question->range_number ?? 5;
                        $labels = ['Rarely', 'Frequently'];
                      @endphp
                      <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <small>{{ $labels[0]}}</small>
                        @for($i = 1; $i <= $range; $i++)
                            <div class="form-check text-center">
                              <label class="custom-control-label d-block" for="range{{$question->id}}{{$i}}">
                                {{ $i }}
                              </label>
                              <input class="form-check-input"
                                    type="radio"
                                    name="answers[{{$question->id}}]"
                                    value="{{$i}}"
                                    id="range{{$question->id}}{{$i}}"
                                    {{$question->is_required ? 'required' : ''}}
                                    {{ (isset($savedAnswers[$question->id]) && $savedAnswers[$question->id] == $i) ? 'checked' : '' }}>                              
                            </div>
                        @endfor
                        <small>{{ $labels[1]}}</small>
                      </div>
                    @endif

                  @if($question->type == 'checkbox')
                    @php
                     
                    @endphp
                      @foreach($question->options as $key => $option)
                        <div class="option-box">
                          <div class="form-check">
                            <!-- <input class="form-check-input" type="checkbox" name="answers[{{$question->id}}][]"
                              value="{{$option->id}}" id="checkboxButton{{ $question->id }}{{ $key }}"
                              {{ isset($savedAnswers[$question->id]) && in_array($option->id, (array)$savedAnswers[$question->id]) ? 'checked' : '' }}
                            > -->
                            <input
                                class="form-check-input" 
                                type="checkbox"
                                name="answers[{{$question->id}}][]"
                                value="{{$option->id}}"                                
                            >
                            <label class="custom-control-label" for="checkboxButton{{ $question->id }}{{ $key }}">
                              {{$option->option_text}}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    @endif

                    @if($question->type == 'text')
                      @if($question->has_image)
                        <div class="mb-2">
                          <img src="{{ asset('storage/' . $question->has_image) }}" width="500">
                        </div>
                      @endif
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <input type="text" class="form-control" name="answers[{{$question->id}}]" 
                        {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"
                        >
                      </div>
                    @endif

                    @if($question->type == 'textarea')
                     @if($question->has_image)
                        <div class="mb-2">
                          <img src="{{ asset('storage/' . $question->has_image) }}" width="500">
                        </div>
                      @endif
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <textarea class="form-control" rows="3" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}>
                           {{ $savedAnswers[$question->id] ?? '' }}
                        </textarea>
                      </div>
                    @endif

                    @if($question->type == 'number')
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <input type="number" class="form-control" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"
                        >
                      </div>
                    @endif

                    @if($question->type == 'email')
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <input type="email" class="form-control" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"
                        >
                      </div>
                    @endif

                    @if($question->type == 'url')
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <input type="url" class="form-control" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"                        
                        >
                      </div>
                    @endif

                    @if($question->type == 'tel')
                      <div class="input-group input-group-static mb-4">
                        <label></label>
                        <input type="tel" class="form-control" name="answers[{{$question->id}}]" {{$question->is_required ? 'required' : ''}}
                        value="{{ $savedAnswers[$question->id] ?? '' }}"
                        >
                      </div>
                    @endif
                    
                  </div>
                @endforeach
        @endif

                <div class="d-flex justify-content-between">
                  @if($index != 0)
                    <button type="button" class="btn btn-secondary prevBtn">
                      Previous
                    </button>
                  @endif

                  @if($index != count($form->sections) - 1)
                    <button type="button" class="btn btn-primary nextBtn">
                      Next Section
                    </button>
                  @else
                    <button type="submit" class="btn btn-success">
                      Submit Form
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </form>
    </div>
  </div>
@endsection

@section('javascript')
  <script>
    let currentStep = 0;
    let totalSteps = $('.section-card').length;
    updateProgress();
    $('.nextBtn').click(function () {
      let current = $('.section-card.active');
      let inputs = current.find('input, textarea, select');
      let isValid = true;
      inputs.each(function () {
        if (!this.checkValidity()) {
          this.reportValidity();
          isValid = false;
          return false;
        }
      });
      // if (!isValid) return;
      saveDraft();
      let index = $('.section-card').index(current);
      goToStep(index + 1);
    });

    $('.prevBtn').click(function () {
      let current = $('.section-card.active');
      let index = $('.section-card').index(current);
      goToStep(index - 1);
    });

    function goToStep(newIndex) {
      let allSections = $('.section-card');
      if (newIndex < 0 || newIndex >= allSections.length) return;
      $('.section-card').removeClass('active');
      allSections.eq(newIndex).addClass('active');
      currentStep = newIndex;
      updateProgress();
      $('html,body').scrollTop(0);
    }

    function updateProgress() {
      let percent = ((currentStep + 1) / totalSteps) * 100;
      $('#progressBar').css('width', percent + '%');
    }

    $('.option-box').click(function (e) {
      // ✅ If user clicked directly on input, do nothing
      if ($(e.target).is('input')) return;

      let input = $(this).find('input');

      if (input.attr('type') === 'checkbox') {
        input.prop('checked', !input.prop('checked'));
        $(this).toggleClass('option-selected');
      }

      if (input.attr('type') === 'radio') {
        $('input[name="' + input.attr('name') + '"]').closest('.option-box').removeClass('option-selected');
        input.prop('checked', true);
        $(this).addClass('option-selected');
      }
    });

    function saveDraft() {
      return;
    }
  </script>

  <script>
    $(document).ready(function () {
      let currentQ = 0;
      let questions = $('.interest-question');
      let totalQ = questions.length;


      $('#nextQuestion').click(function () {
        if (currentQ < totalQ - 1) {
          questions.eq(currentQ).removeClass('active');
          currentQ++;
          questions.eq(currentQ).addClass('active');
        }
      });

      $('#prevQuestion').click(function () {
        if (currentQ > 0) {
          questions.eq(currentQ).removeClass('active');
          currentQ--;
          questions.eq(currentQ).addClass('active');
        }
      });

    });
  </script>
@endsection