@extends('layouts.app')

@section('title', 'View Resource')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="mb-0 h4 font-weight-bolder">{{ $resource->title }}</h3>
                    <p class="text-secondary">Resource by {{ $resource->assignedBy->name }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('resource.my-resources') }}" class="btn btn-secondary">
                        <i class="material-symbols-rounded">arrow_back</i> Back to Resources
                    </a>
                </div>
            </div>

            @include('includes/errors/validation-errors')

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Resource Type</h6>
                            <p class="h5 mb-0" style="visibility: hidden;">-</p>
                            <p class="h5 mb-0">{{ $resource->getResourceTypeLabel() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted">Assigned By</h6>
                            <p class="h5 mb-0">{{ $resource->assignedBy->name }}</p>
                            <small class="text-secondary">{{ $resource->assigned_at->format('M d, Y') }}</small>
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
                            <h6 class="text-muted">Status</h6>
                            <p class="h5 mb-0" style="visibility: hidden;">-</p>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Resource Content</h5>
                            @if($resource->status === 'accessed')
                                <form method="POST" action="{{ route('resource.mark-completed', $resource) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="material-symbols-rounded">check</i> Click to Mark as Completed
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($resource->file_path)
                                <div class="mb-3">
                                    <h6>
                                        <i class="material-symbols-rounded">description</i> File
                                    </h6>
                                    <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                        class="btn btn-info">
                                        <i class="material-symbols-rounded">download</i> Download / View File
                                    </a>
                                </div>
                            @endif
                            @if($resource->resource_url)
                                <div>
                                    <h6 style="display:flex;gap:4px;">
                                        <i class="material-symbols-rounded">link</i> External Link
                                    </h6>
                                    <a href="{{ $resource->resource_url }}" target="_blank" class="btn btn-primary">
                                        <i class="material-symbols-rounded">open_in_new</i> Open Resource
                                    </a>
                                </div>
                            @endif
                            @if(!$resource->file_path && !$resource->resource_url)
                                <p class="text-muted">No resources attached.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($resource->overall_remarks)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Mentor Feedback/Remark</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-light border border-warning mb-3">
                                    <strong class="text-warning">Mentor Feedback:</strong>
                                    <p class="mb-0 mt-2">{{ $resource->overall_remarks }}</p>
                                </div>

                                @if($resource->student_response_to_remarks)
                                    <div class="alert alert-light border border-info">
                                        <strong class="text-info">Your Response:</strong>
                                        <p class="mb-0 mt-2">{{ $resource->student_response_to_remarks }}</p>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('resource.respond-overall', $resource) }}" class="mt-3">
                                        @csrf
                                        <div class="mb-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Your Response to Feedback</label>
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
                                                <strong class="text-warning">Mentor Remark:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->admin_remark }}</p>
                                            </div>
                                        @endif

                                        @if($remark->student_response)
                                            <div class="alert alert-light border border-info">
                                                <strong class="text-info">Your Response:</strong>
                                                <p class="mb-0 mt-2">{{ $remark->student_response }}</p>
                                                <small class="text-muted">{{ $remark->responded_at->format('M d, Y H:i') }}</small>
                                            </div>
                                        @elseif($remark->admin_remark)
                                            <form method="POST" action="{{ route('resource-remark.respond', $remark) }}" class="mt-3"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <div class="input-group input-group-static mb-4">
                                                        <label>Your Response</label>
                                                        <textarea name="student_response" class="form-control" rows="3"
                                                            required></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="input-group input-group-static mb-4">
                                                        <label for="file_path">Upload File (PDF/DOC)</label>
                                                        <input type="file" name="file_path" id="file_path"
                                                            class="form-control @error('file_path') is-invalid @enderror"
                                                            accept=".pdf,.doc,.docx">
                                                        @if($remark->file_path)
                                                            <div class="mt-2">
                                                                <small class="text-success">
                                                                    <i class="material-symbols-rounded">check_circle</i> File:
                                                                    {{ basename($resource->file_path) }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                        @error('file_path')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
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
                                <p class="text-muted">No remarks yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection