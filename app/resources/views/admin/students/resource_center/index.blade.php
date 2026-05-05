@extends('layouts.app')

@section('title', 'Resource Management')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">Resource Center</h3>
                    <p class="text-secondary">Manage and assign learning resources to students</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('resource.create') }}" class="btn btn-primary">
                        <i class="material-symbols-rounded">add</i> Add Resource
                    </a>
                    <a href="{{ route('resource.deadlines') }}" class="btn btn-warning">
                        <i class="material-symbols-rounded">schedule</i> Track Deadlines
                    </a>
                </div>
            </div>
            @include('includes/errors/session-message')

            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                        <h6 class="text-white text-capitalize ps-3">Assigned Resources</h6>
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
                                    <th>Assigned By</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge bg-light text-dark">
                                                                        {{ $resource->student?->name ?? "Duplicate" }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $resource->title }}</td>
                                                                <td>
                                                                    <small style="display: flex;gap: 7px;">
                                                                        @if($resource->resource_type === 'pdf')
                                                                            <i class="material-symbols-rounded">description</i>
                                                                        @elseif($resource->resource_type === 'video')
                                                                            <i class="material-symbols-rounded">play_circle</i>
                                                                        @elseif($resource->resource_type === 'article')
                                                                            <i class="material-symbols-rounded">article</i>
                                                                        @else
                                                                            <i class="material-symbols-rounded">folder</i>
                                                                        @endif
                                                                        {{ ucfirst($resource->resource_type) }}
                                                                    </small>
                                                                </td>
                                                                <td>{{ $resource->assignedBy->name }}</td>
                                                                <td>
                                                                    @if($resource->deadline)
                                                                        <small class="text-muted">{{ $resource->deadline->format('M d, Y') }}</small>
                                                                        <br>
                                                                        <small class="text-{{ $resource->isOverdue() ? 'danger' : 'success' }}">
                                                                            {{ $resource->getDeadlineStatus() }}
                                                                        </small>
                                                                    @else
                                                                        <small class="text-muted">---</small>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-{{ match ($resource->status) {
                                        'pending' => 'danger',
                                        'accessed' => 'primary',
                                        'completed' => 'success',
                                        '' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                                                        {{ ucfirst($resource->status === '' ? "Duplicate" : $resource->status) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('resource.show', $resource) }}" class="btn btn-sm btn-info"
                                                                        title="View">
                                                                        <i class="material-symbols-rounded">visibility</i>
                                                                    </a>
                                                                    <form method="POST" action="{{ route('resource.clone', $resource) }}"
                                                                        style="display:inline;" title="Clone">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm btn-secondary">
                                                                            <i class="material-symbols-rounded">content_copy</i>
                                                                        </button>
                                                                    </form>
                                                                    <a href="{{ route('resource.edit', $resource) }}" class="btn btn-sm btn-warning"
                                                                        title="Edit">
                                                                        <i class="material-symbols-rounded">edit</i>
                                                                    </a>
                                                                    <form method="POST" action="{{ route('resource.destroy', $resource) }}"
                                                                        style="display:inline;" onsubmit="return confirm('Delete this resource?')"
                                                                        title="Delete">
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
                                            No resources assigned yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $resources->links() }}
            </div>
        </div>
    </main>
@endsection