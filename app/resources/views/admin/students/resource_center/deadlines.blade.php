@extends('layouts.app')

@section('title', 'Track Deadlines')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-0 h4 font-weight-bolder">Overdue Resources</h3>
                    <p class="text-secondary">Monitor and track resources with passed deadlines</p>
                </div>
            </div>

            @include('includes/errors/session-message')

            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                        <h6 class="text-white text-capitalize ps-3">Overdue Resources</h6>
                    </div>
                </div>
                <div class="card-body pb-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Resource Title</th>
                                    <th>Type</th>
                                    <th>Deadline</th>
                                    <th>Days Overdue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overdueResources as $resource)
                                                            <tr class="{{ $resource->daysRemaining() < -7 ? 'table-danger' : '' }}">
                                                                <td>
                                                                    <span class="badge bg-light text-dark">
                                                                        {{ $resource->student?->name ?? "Duplicate" }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $resource->title }}</td>
                                                                <td>{{ ucfirst($resource->resource_type) }}</td>
                                                                <td>
                                                                    @if($resource->deadline)
                                                                        {{ $resource->deadline->format('M d, Y') }}
                                                                    @else
                                                                        ---
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-danger">
                                                                        {{ abs($resource->daysRemaining()) }} days overdue
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ match ($resource->status) {
                                        'pending' => 'secondary',
                                        'accessed' => 'primary',
                                        'completed' => 'success',
                                        default => 'secondary'
                                    } }}">
                                                                        {{ ucfirst($resource->status) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('resource.show', $resource) }}" class="btn btn-sm btn-info">
                                                                        <i class="material-symbols-rounded">visibility</i> View
                                                                    </a>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No overdue resources! Great job!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $overdueResources->links() }}
            </div>
        </div>
    </main>
@endsection