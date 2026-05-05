@extends('layouts.app')

@section('title', 'My Resources')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="mb-0 h4 font-weight-bolder">My Learning Resources</h3>
                    <p class="text-secondary">Access learning resources shared by your mentors</p>
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
                                    <th>Resource Title</th>
                                    <th>Type</th>
                                    <th>Assigned By</th>
                                    <th>Assigned Date</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                <tr>
                                    <td>
                                        <strong>{{ $resource->title }}</strong>
                                        @if(!empty($resource->description))
                                            <br>
                                            <small class="text-muted">{{ Str::limit($resource->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small style="display:flex;gap:4px;">
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
                                        <small class="text-muted">{{ $resource->assigned_at->format('M d, Y') }}</small>
                                    </td>
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
            default => 'secondary'
        } }}">
                                            {{ ucfirst($resource->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('resource.view', $resource) }}" class="btn btn-sm btn-info">
                                            <i class="material-symbols-rounded">visibility</i> View
                                        </a>
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