@extends('layouts.app')

@section('title', 'Track Deadlines')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-0 h4 font-weight-bolder">Overdue Tasks</h3>
                    <p class="text-secondary">Monitor and track tasks that have passed their deadline</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Form</th>
                                <th>Deadline</th>
                                <th>Days Overdue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($overdueTasks as $task)
                                                    <tr class="{{ $task->daysRemaining() < -7 ? 'table-danger' : '' }}">
                                                        <td>
                                                            <span class="badge bg-light text-dark">
                                                                {{ $task->student?->name ?? "Duplicate" }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $task->form->title }}</td>
                                                        <td>
                                                            @if($task->deadline)
                                                                {{ $task->deadline->format('M d, Y') }}
                                                            @else
                                                                ---
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-danger">
                                                                {{ abs($task->daysRemaining()) }} days overdue
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ match ($task->status) {
                                    'pending' => 'secondary',
                                    'in_progress' => 'warning',
                                    'submitted' => 'info',
                                    'completed' => 'success',
                                    'overdue' => 'danger',
                                    default => 'secondary'
                                } }}">
                                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('task-completion.show', $task) }}" class="btn btn-sm btn-info">
                                                                <i class="material-symbols-rounded">visibility</i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No overdue tasks! Great job!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $overdueTasks->links() }}
            </div>
        </div>
    </main>
@endsection