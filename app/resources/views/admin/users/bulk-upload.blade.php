@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-0 mb-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Bulk Upload Users</h3>
                    <p class="mb-1">
                        Upload multiple students at once using an Excel file.
                    </p>
                </div>

                <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            @include('includes/errors/validation-errors')
                            @include('includes/errors/session-message')
                            <form action="{{ route('bulk-store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Upload Excel File</label>
                                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv"
                                                required>
                                            <small class="form-text text-muted mt-2">
                                                Supported formats: Excel (.xlsx, .xls) and CSV. Maximum file size: 5MB
                                            </small>
                                            @error('file')
                                                <div class="mt-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info" role="alert">
                                    <h6 class="alert-heading mb-2">Excel File Format Requirements</h6>
                                    <p class="mb-2">Your file should have the following columns in this order:</p>
                                    <ul class="mb-0">
                                        <li><strong>Column A (Name):</strong> Full name of the student</li>
                                        <li><strong>Column B (Email):</strong> Email address (must be unique)</li>
                                        <li><strong>Column C (Password):</strong> Password for the account</li>
                                        <li><strong>Column D (Mobile):</strong> Phone number (must be unique)</li>
                                        <li><strong>Column E (Gender):</strong> Gender (e.g., Male, Female, Other)</li>
                                        <li><strong>Column F (Address):</strong> Address</li>
                                    </ul>
                                    <p class="mt-2 mb-0"><em>Note: The first row should be headers and will be skipped. All
                                            users will be assigned the "Student" role.</em></p>
                                </div>

                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading mb-2">Important Notes</h6>
                                    <ul class="mb-0">
                                        <li>Email and Mobile addresses must be unique across the system</li>
                                        <li>All users will automatically be assigned the "Student" role</li>
                                        <li>If any rows have errors, those users will not be created but others will be
                                            processed</li>
                                        <li>Passwords will be securely hashed before storing</li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-success">Upload Users</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            @include('includes/footer-text')
        </div>
    </main>
@endsection