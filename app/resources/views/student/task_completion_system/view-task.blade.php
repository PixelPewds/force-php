@extends('layouts.app')

@section('title', 'View Task')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">{{ $taskCompletion->form->title }}</h3>
                    <p class="text-secondary">Task assigned by {{ $taskCompletion->assignedBy->name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('task-completion.my-tasks') }}" class="btn btn-secondary">
                        <i class="material-symbols-rounded">arrow_back</i> Back to Tasks
                    </a>
                </div>
            </div>

            @include('includes/errors/validation-errors')

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Assigned By</h6>
                            <p class="h5 mb-0">{{ $taskCompletion->assignedBy->name }}</p>
                            <small class="text-secondary">{{ $taskCompletion->assigned_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>

                @if (isset($taskCompletion->deadline))
                    <div class="col-md-3">
                        <div class="card border-{{ $taskCompletion->isOverdue() ? 'danger' : 'success' }}">
                            <div class="card-body">
                                <h6 class="text-muted">Deadline</h6>
                                <p class="h5 mb-0">{{ $taskCompletion->deadline->format('M d, Y') }}</p>
                                <small class="text-{{ $taskCompletion->isOverdue() ? 'danger' : 'success' }}">
                                    {{ $taskCompletion->getDeadlineStatus() }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Status</h6>
                            <p class="h5 mb-0" style="visibility: hidden;">-</p>
                            @if ($taskCompletion->form->formSubmissions->first() && $taskCompletion->form->formSubmissions->first()->status === 'submitted')
                                <p class="text-sm font-weight-normal mb-0">
                                    <span class="badge bg-success">
                                        {{ ucfirst('Completed') }}
                                    </span>
                                </p>
                            @else
                                <p class="text-sm font-weight-normal mb-0">
                                    <span class="badge bg-primary"> {{ ucfirst('In Progress') }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Progress</h6>
                            <p class="h5 mb-0" style="visibility: hidden;">-</p>
                            @if ($taskCompletion->form->formSubmissions->first() && $taskCompletion->form->formSubmissions->first()->status === 'submitted')
                                <span style="font-weight:bold;font-size:14px;">100%</span>
                            @else
                                <span
                                    style="font-weight:bold;font-size:14px;">{{ $taskCompletion->completion_percentage }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Questions & Remarks</h5>
                        </div>
                        <div class="card-body">
                            @if($taskCompletion->questionRemarks->count())
                                @foreach($taskCompletion->questionRemarks as $remark)
                                    <div class="mb-4 pb-4 border-bottom">
                                        <h6 class="text-dark mb-2">
                                            Q: {{ $remark->question->question_text ?? 'N/A' }}
                                        </h6>
                                        @if($remark->admin_remark)
                                            <div class="alert alert-light border border-warning mb-3">
                                                <strong class="text-warning">Mentor Remark:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->admin_remark }}</p>
                                            </div>
                                        @endif

                                        @if($remark->student_response)
                                            <div class="alert alert-light border border-info mb-3">
                                                <strong class="text-info">Your Response:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->student_response }}</p>
                                                <small class="text-muted">{{ $remark->responded_at->format('M d, Y H:i') }}</small>
                                            </div>
                                        @elseif($remark->admin_remark)
                                            <form method="POST" action="{{ route('task-completion-remark.respond', $remark) }}"
                                                class="mt-3">
                                                @csrf
                                                <div class="mb-3">
                                                    <div class="input-group input-group-static mb-4">
                                                        <label>Your Response</label>
                                                        <textarea name="student_response" class="form-control" rows="3"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="material-symbols-rounded">send</i> Submit Response
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No remarks added yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($taskCompletion->overall_remarks)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Overall Feedback</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-light border border-warning mb-3">
                                    <strong class="text-warning">Mentor Feedback:</strong>
                                    <p class="mb-0 mt-2">{{ $taskCompletion->overall_remarks }}</p>
                                </div>

                                @if($taskCompletion->student_response_to_remarks)
                                    <div class="alert alert-light border border-info">
                                        <strong class="text-info">Your Response:</strong>
                                        <p class="mb-0 mt-2">{{ $taskCompletion->student_response_to_remarks }}</p>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('task-completion.respond-overall', $taskCompletion) }}"
                                        class="mt-3">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Your Response to Overall Feedback</label>
                                                <textarea name="student_response_to_remarks" class="form-control" rows="4"
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-symbols-rounded">send</i> Submit Response
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection