@extends('layouts.app')

@section('title', 'Edit Task Assignment')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-4">
      <div class="row mb-4">
        <div class="col-md-12">
          <h2 class="text-dark">Edit Task Assignment</h2>
          <p class="text-secondary">Update task details for {{ $taskCompletion->student->name }}</p>
        </div>
      </div>

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Validation Errors:</strong>
          <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-body">
          <form method="POST" action="{{ route('task-completion.update', $taskCompletion) }}">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror"
                  required>
                  @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ $taskCompletion->student_id == $student->id ? 'selected' : '' }}>
                      {{ $student->name }}
                    </option>
                  @endforeach
                </select>
                @error('student_id')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="form_id" class="form-label">Form <span class="text-danger">*</span></label>
                <select name="form_id" id="form_id" class="form-control @error('form_id') is-invalid @enderror" required>
                  @foreach($forms as $form)
                    <option value="{{ $form->id }}" {{ $taskCompletion->form_id == $form->id ? 'selected' : '' }}>
                      {{ $form->title }}
                    </option>
                  @endforeach
                </select>
                @error('form_id')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                <input type="date" name="deadline" id="deadline"
                  class="form-control @error('deadline') is-invalid @enderror"
                  value="{{ $taskCompletion->deadline->format('Y-m-d') }}" required>
                @error('deadline')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                  <option value="pending" {{ $taskCompletion->status == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="in_progress" {{ $taskCompletion->status == 'in_progress' ? 'selected' : '' }}>In Progress
                  </option>
                  <option value="submitted" {{ $taskCompletion->status == 'submitted' ? 'selected' : '' }}>Submitted
                  </option>
                  <option value="completed" {{ $taskCompletion->status == 'completed' ? 'selected' : '' }}>Completed
                  </option>
                  <option value="overdue" {{ $taskCompletion->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('status')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="col-md-4 mb-3">
                <label for="completion_percentage" class="form-label">Completion %</label>
                <input type="number" name="completion_percentage" id="completion_percentage" class="form-control" min="0"
                  max="100" value="{{ $taskCompletion->completion_percentage }}">
              </div>
            </div>

            <div class="mb-3">
              <label for="admin_remarks" class="form-label">Initial Remarks</label>
              <textarea name="admin_remarks" id="admin_remarks" class="form-control"
                rows="3">{{ $taskCompletion->admin_remarks }}</textarea>
            </div>

            <div class="mb-3">
              <label for="overall_remarks" class="form-label">Overall Remarks</label>
              <textarea name="overall_remarks" id="overall_remarks" class="form-control"
                rows="3">{{ $taskCompletion->overall_remarks }}</textarea>
              <small class="text-muted">Remarks after reviewing student submission</small>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="material-symbols-rounded">check</i> Update Task
              </button>
              <a href="{{ route('task-completion.show', $taskCompletion) }}" class="btn btn-secondary">
                <i class="material-symbols-rounded">close</i> Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection