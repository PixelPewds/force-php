@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">{{ $taskCompletion->form->title }}</h3>
                    <p class="text-secondary">Task Details for {{ $taskCompletion->student->name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('task-completion.edit', $taskCompletion) }}" class="btn btn-warning">
                        <i class="material-symbols-rounded">edit</i> Edit
                    </a>
                </div>
            </div>
            @include('includes/errors/validation-errors')

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Student</h6>
                            <p class="h5 mb-0">{{ $taskCompletion->student->name }}</p>
                            <small class="text-secondary">{{ $taskCompletion->student->email }}</small>
                        </div>
                    </div>
                </div>
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
                            <p class="h5 mb-0" style="visibility:hidden;">-</p>
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
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Mentor Feedback & Student Response</h5>
                        </div>
                        <div class="card-body">
                            @if($taskCompletion->overall_remarks)
                                <div class="mb-3">
                                    <h6>Mentor Feedback:</h6>
                                    <p>{{ $taskCompletion->overall_remarks }}</p>
                                </div>
                            @else
                                <form method="POST" action="{{ route('respond-admin-overall', $taskCompletion) }}" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Add Mentor/Overall Feedback</label>
                                            <textarea name="overall_remarks" class="form-control" rows="4"
                                                placeholder="Remark..." required></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-symbols-rounded">send</i> Submit Response
                                    </button>
                                </form>
                            @endif

                            @if($taskCompletion->student_response_to_remarks)
                                <div>
                                    <h6 class="text-primary">Student Response:</h6>
                                    <p>{{ $taskCompletion->student_response_to_remarks }}</p>
                                </div>
                            @else
                                <p class="text-muted">No student response yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($taskCompletion->form->formSubmissions->first() && $taskCompletion->form->formSubmissions->first()->status === 'submitted')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card my-4">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                                    <h6 class="text-white text-capitalize ps-3">Question-Answere</h6>
                                </div>
                            </div>
                            <div class="card-body  pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table table-bordered align-items-center mb-0" id="activity-datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Question
                                                </th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Answere
                                                </th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Admin Remark</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Student Response
                                                </th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Date
                                                </th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($taskCompletion->form->formSubmissions->first()->responses as $response)
                                                @php
                                                    $remark = $taskCompletion->questionRemarks
                                                        ->firstWhere('question_id', $response->question->id);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <p class="text-sm font-weight-normal mb-0">
                                                            {{ $response->question->question_text ?? 'N/A' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-normal mb-0">
                                                            @switch($response->question->type)
                                                                @case('radio')
                                                                    {{ $response->option->option_text ?? '-' }}
                                                                    @break

                                                                @case('checkbox')
                                                                    {{ $response->formatted_answer ?? '-' }}
                                                                    @break

                                                                @default
                                                                    {{ $response->answer ?? '-' }}
                                                            @endswitch
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @if($remark && $remark->admin_remark)
                                                            <p class="text-sm font-weight-normal mb-0">
                                                                {{ $remark->admin_remark }}
                                                            </p>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($remark && $remark->student_response)
                                                            <small class="text-primary">{{ $remark->student_response }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($remark && $remark->responded_at)
                                                            <small class="text-muted">
                                                                {{ $remark->responded_at->format('M d, Y') }}
                                                            </small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                                <h6 class="text-white text-capitalize ps-3">Question-wise Remarks</h6>
                            </div>
                        </div>
                        <div class="card-body  pb-2">
                            <div class="mt-4">
                                <h6>Add Feedback to Question</h6>
                            </div>
                            <form method="POST" action="{{ route('task-completion.add-remark', $taskCompletion) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label for="question_id">Select Question</label>
                                            <select name="question_id" class="form-control" id="question_id" required>
                                                <option value=""> Select Question </option>
                                                @foreach($taskCompletion->form->sections as $section)
                                                    @foreach($section->questions as $question)
                                                        <option value="{{ $question->id }}">{{ $question->question_text }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Mentor Feedback</label>
                                            <textarea name="admin_remark" class="form-control" placeholder="Enter remark..."
                                                rows="5" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-20">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

    </main>
@endsection

@section('javascript')
    <script>
        var table = new DataTable('#activity-datatable', {
            scrollX: false,
            "lengthChange": false,
            paging: false,
            "bInfo": false,
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