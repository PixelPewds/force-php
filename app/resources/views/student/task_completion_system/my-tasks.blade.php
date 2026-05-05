@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-0 h4 font-weight-bolder">My Assigned Tasks</h3>
                    <p class="text-secondary">View and respond to tasks assigned by mentors</p>
                </div>
            </div>

            @include('includes/errors/validation-errors')

            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                        <h6 class="text-white text-capitalize ps-3">My Assigned Tasks</h6>
                    </div>
                </div>
                <div class="card-body  pb-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Form</th>
                                    <th>Assigned By</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <p class="text-sm font-weight-normal mb-0">{{ $task->form->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-normal mb-0">{{ $task->assignedBy->name }}</p>
                                        </td>
                                        <td>
                                            @if($task->deadline)
                                                <p class="text-sm font-weight-normal mb-0">
                                                    <small class="text-muted">{{ $task->deadline->format('M d, Y') }}</small>
                                                    <br>
                                                    <small class="text-{{ $task->isOverdue() ? 'danger' : 'success' }}">
                                                        {{ $task->getDeadlineStatus() }}
                                                    </small>
                                                </p>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            @if ($task->form->formSubmissions->first() && $task->form->formSubmissions->first()->status === 'submitted')
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
                                        </td>
                                        <td>
                                            @if ($task->form->formSubmissions->first() && $task->form->formSubmissions->first()->status === 'submitted')
                                                <div class=" progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width:100%">
                                                        100%
                                                    </div>
                                                </div>
                                            @else
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-warning" role="progressbar"
                                                        style="width: {{ $task->completion_percentage ? $task->completion_percentage : 20 }}%">
                                                        {{ $task->completion_percentage ? $task->completion_percentage : 10 }}%
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($task->form->formSubmissions->first() && $task->form->formSubmissions->first()->status === 'submitted')
                                                <a href="{{ route('task-completion.view', $task) }}" class="btn btn-sm btn-info">
                                                    <i class="material-symbols-rounded">visibility</i> View
                                                </a>
                                            @else
                                                <a class="btn btn-warning" target="_blank"
                                                    href="{{ route('startForm', [$task->form_id, 'task_id' => $task->id]) }}">Complete
                                                    Task</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No tasks assigned yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
        </div>
    </main>
@endsection