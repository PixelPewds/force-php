@extends('layouts.app')

@section('title', 'Assign New Task')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row mb-4">
        <div class="col-md-12">
          <h3 class="mb-0 h4 font-weight-bolder">Assign New Task to Student</h3>
          <p class="text-secondary">Create a new task assignment with deadline</p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          @include('includes/errors/validation-errors')
          <form method="POST" action="{{ route('task-completion.store') }}">
            @csrf
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static mb-4">
                  <label for="student_id">Student <span class="text-danger">*</span></label>
                  <select name="student_id" id="student_id"
                    class="js-select2 form-control @error('student_id') is-invalid @enderror" required>
                    <option value="">Select Student </option>
                    @foreach($students as $student)
                      <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} ({{ $student->email }})
                      </option>
                    @endforeach
                  </select>
                </div>
                @error('student_id')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static mb-4">
                  <label for="form_id">Form <span class="text-danger">*</span></label>
                  <select name="form_id" id="form_id"
                    class="js-select2 form-control @error('form_id') is-invalid @enderror" required>
                    <option value="">Select Form </option>
                    @foreach($forms as $form)
                      <option value="{{ $form->id }}" {{ old('form_id') == $form->id ? 'selected' : '' }}>
                        {{ $form->title }}
                      </option>
                    @endforeach
                  </select>
                </div>

                @error('form_id')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="input-group input-group-static mb-4">
                  <label for="deadline">Deadline </label>
                  <input type="date" name="deadline" id="deadline"
                    class="form-control @error('deadline') is-invalid @enderror">
                  @error('deadline')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                  <small class="text-muted">Must be today or in the future</small>
                </div>
              </div>
            </div>

            <!-- <div class="mb-3">
                                              <div class="input-group input-group-static mb-4">
                                                <label for="admin_remarks">Initial Instructions/Remarks</label>
                                                <textarea name="admin_remarks" id="admin_remarks" class="form-control" rows="4"
                                                  placeholder="Enter any initial remarks or instructions for the student...">{{ old('admin_remarks') }}</textarea>
                                                <small class="text-muted">These remarks will be visible to the student</small>
                                              </div>
                                            </div> -->

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="material-symbols-rounded">check</i> Assign Task
              </button>
              <a href="{{ route('task-completion.index') }}" class="btn btn-secondary">
                <i class="material-symbols-rounded">close</i> Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection