@extends('layouts.app')

@section('title', 'Task Assignments')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">Assigned Tasks</h3>
                    <p class="text-secondary">Manage student task assignments and track deadlines</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('task-completion.create') }}" class="btn btn-primary">
                        <i class="material-symbols-rounded">add</i> Assign New Task
                    </a>
                    <a href="{{ route('task-completion.deadlines') }}" class="btn btn-warning">
                        <i class="material-symbols-rounded">schedule</i> Track Deadlines
                    </a>
                </div>
            </div>
            @include('includes/errors/session-message')

            <div class="card my-4">

                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                        <h6 class="text-white text-capitalize ps-3">Assigned Tasks</h6>
                    </div>
                </div>
                <div class="card-body  pb-2">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Form</th>
                                    <th>Assigned By</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Completion</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $task->student->name }}
                                            </span>
                                        </td>
                                        <td>{{ $task->form->title }}</td>
                                        <td>{{ $task->assignedBy->name }}</td>
                                        <td>
                                            @if (isset($task->deadline))
                                                <small class="text-muted">{{ $task->deadline->format('M d, Y') }}</small>
                                                <br>
                                                <small class="text-{{ $task->isOverdue() ? 'danger' : 'success' }}">
                                                    {{ $task->getDeadlineStatus() }}
                                                </small>
                                            @else
                                                <small class="text-muted">---</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($task->submission->status) && $task->submission->status === 'submitted')
                                                <p class="text-sm font-weight-normal mb-0">
                                                    <span class="badge bg-success">
                                                        {{ ucfirst('Completed') }}
                                                    </span>
                                                </p>
                                            @else
                                                <p class="text-sm font-weight-normal mb-0">
                                                    <span class="badge bg-danger"> {{ ucfirst('In Progress') }}
                                                    </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($task->submission->status) && $task->submission->status === 'submitted')
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
                                            <a href="{{ route('task-completion.show', $task) }}" class="btn btn-sm btn-info">
                                                <i class="material-symbols-rounded">visibility</i>
                                            </a>
                                            <a href="{{ route('task-completion.edit', $task) }}" class="btn btn-sm btn-warning">
                                                <i class="material-symbols-rounded">edit</i>
                                            </a>
                                            <form method="POST" action="{{ route('task-completion.destroy', $task) }}"
                                                style="display:inline;"
                                                onsubmit="return confirm('Delete this task assignment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="material-symbols-rounded">delete</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No tasks assigned yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </main>
@endsection