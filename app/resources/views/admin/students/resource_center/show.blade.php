@extends('layouts.app')

@section('title', 'Resource Details')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">{{ $resource->title }}</h3>
                    <p class="text-secondary">Resource Details for {{ $resource->student->name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('resource.edit', $resource) }}" class="btn btn-warning">
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
                            <p class="h5 mb-0">{{ $resource->student->name }}</p>
                            <small class="text-secondary">{{ $resource->student->email }}</small>
                        </div>
                    </div>
                </div>

                @if($resource->deadline)
                    <div class="col-md-3">
                        <div class="card border-{{ $resource->isOverdue() ? 'danger' : 'success' }}">
                            <div class="card-body">
                                <h6 class="text-muted">Deadline</h6>
                                <p class="h5 mb-0">{{ $resource->deadline->format('M d, Y') }}</p>
                                <small class="text-{{ $resource->isOverdue() ? 'danger' : 'success' }}">
                                    {{ $resource->getDeadlineStatus() }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Assigned By</h6>
                            <p class="h5 mb-0">{{ $resource->assignedBy->name }}</p>
                            <small class="text-secondary">{{ $resource->assigned_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Status</h6>
                            <p class="h5 mb-0" style="visibility:hidden;">-</p>
                            <span class="badge bg-{{ match ($resource->status) {
        'pending' => 'danger',
        'accessed' => 'primary',
        'completed' => 'success',
        default => 'secondary'
    } }}">
                                {{ ucfirst($resource->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Description</h5>
                        </div>
                        <div class="card-body">
                            @if($resource->description)
                                <p>{{ $resource->description }}</p>
                            @else
                                <p class="text-muted">No description provided.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Resource Links</h5>
                            <p class="h5 mb-0">
                            <h6 class="text-muted">Resource Type:</h6> {{ $resource->getResourceTypeLabel() }}
                            </p>
                        </div>
                        <div class="card-body">
                            @if($resource->file_path)
                                <div class="mb-0">
                                    <h6>File:</h6>
                                    <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="material-symbols-rounded">download</i> Download File
                                    </a>
                                </div>
                            @endif
                            @if($resource->resource_url)
                                <div class="mb-0">
                                    <h6>URL:</h6>
                                    <a href="{{ $resource->resource_url }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="material-symbols-rounded">open_in_new</i> Open Link
                                    </a>
                                </div>
                            @endif
                            @if(!$resource->file_path && !$resource->resource_url)
                                <p class="text-muted">No files or links attached.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Overall Remarks & Student Response</h5>
                        </div>
                        <div class="card-body">
                            @if($resource->overall_remarks)
                                <div class="mb-3">
                                    <h6>Admin Remarks:</h6>
                                    <p>{{ $resource->overall_remarks }}</p>
                                </div>
                            @else
                                @if($resource->status === 'completed')
                                    <div class="mb-3">
                                        <form method="POST" action="{{ route('resource.add-remark', $resource) }}">
                                            @csrf
                                            <div class="input-group input-group-static mb-4">
                                                <label for="overall_remarks">Overall Feedback</label>
                                                <textarea name="overall_remarks" id="overall_remarks" class="form-control"
                                                    rows="3">{{ $resource->overall_remarks }}</textarea>
                                                <small class="text-muted">Feedback after student has accessed the resource</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="material-symbols-rounded">send</i> Submit Feedback
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <p class="text-muted">No Mentor added Feedback yet.</p>
                                @endif
                            @endif

                            @if($resource->student_response_to_remarks)
                                <div>
                                    <h6 class="text-primary">Student Response:</h6>
                                    <p>{{ $resource->student_response_to_remarks }}</p>
                                </div>
                            @else
                                <p class="text-muted">No student response yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Mentor Feedback/Remark</h5>
                        </div>
                        <div class="card-body">
                            @if($resource->remarks->count())
                                @foreach($resource->remarks as $remark)
                                    <div class="mb-4 pb-4 border-bottom">
                                        @if($remark->admin_remark)
                                            <div class="alert alert-light border border-warning mb-3">
                                                <strong class="text-warning">Admin Remark:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->admin_remark }}</p>
                                            </div>
                                        @endif

                                        @if($remark->student_response)
                                            <div class="alert alert-light border border-info">
                                                <strong class="text-info">Student Response:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->student_response }}</p>
                                                <small class="text-muted">{{ $remark->responded_at->format('M d, Y H:i') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No remarks added yet.</p>
                            @endif

                            @if($resource->status === 'completed')
                                <div class="mt-4">
                                    <h6>Mentor Feedback/Remark</h6>
                                    <form method="POST" action="{{ route('resource.add-remark', $resource) }}">
                                        @csrf
                                        <div class="input-group input-group-static mb-4">
                                            <textarea name="admin_remark" class="form-control mb-3" rows="3"
                                                placeholder="Enter your remark..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-symbols-rounded">send</i> Submit Feedback
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection