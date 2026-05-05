@extends('layouts.app')

@section('title', 'Add Resource')

@section('content')
@include('includes/sidenav')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <h3 class="mb-0 h4 font-weight-bolder">Add New Resource</h3>
                <p class="text-secondary">Assign learning resources to students</p>
            </div>
        </div>
        @include('includes/errors/validation-errors')
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('resource.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                            <label for="student_id" >Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="js-select2 form-control @error('student_id') is-invalid @enderror" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                            <label for="resource_type" >Resource Type <span class="text-danger">*</span></label>
                            <select name="resource_type" id="resource_type" class="form-control @error('resource_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                @foreach($resourceTypes as $type)
                                    <option value="{{ $type }}" {{ old('resource_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('resource_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            </div>

                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group input-group-static mb-4">
                        <label for="title" >Resource Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Enter resource title" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        </div>

                    </div>

                    <div class="mb-3">
                        <div class="input-group input-group-static mb-4">

                        <label for="description" >Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" 
                                  placeholder="Enter resource description...">{{ old('description') }}</textarea>
                                  </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">

                            <label for="file_path" >Upload File (PDF/DOC)</label>
                            <input type="file" name="file_path" id="file_path" class="form-control @error('file_path') is-invalid @enderror" 
                                   accept=".pdf,.doc,.docx">
                            <small class="text-muted">Max 10MB, accepted: PDF, DOC, DOCX</small>
                            @error('file_path')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">

                            <label for="resource_url" >Resource URL/Link</label>
                            <input type="url" name="resource_url" id="resource_url" class="form-control @error('resource_url') is-invalid @enderror" 
                                   value="{{ old('resource_url') }}" placeholder="https://example.com">
                            @error('resource_url')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group input-group-static mb-4">

                        <label for="deadline" >Deadline (Optional)</label>
                        <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" 
                               value="{{ old('deadline') }}">
                        <small class="text-muted">Leave empty if no deadline</small>
                        @error('deadline')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        </div>
                    </div>

                    <!-- <div class="mb-3">
                        <div class="input-group input-group-static mb-4">

                        <label for="admin_remarks" >Initial Instructions/Remarks</label>
                        <textarea name="admin_remarks" id="admin_remarks" class="form-control" rows="3" 
                                  placeholder="Enter any instructions or remarks for the student...">{{ old('admin_remarks') }}</textarea>
                        <small class="text-muted">These remarks will be visible to the student</small>
                        </div>
                    </div> -->

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-symbols-rounded">check</i> Add Resource
                        </button>
                        <a href="{{ route('resource.index') }}" class="btn btn-secondary">
                            <i class="material-symbols-rounded">close</i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
