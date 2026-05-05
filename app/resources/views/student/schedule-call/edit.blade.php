@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-0 mb-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Edit Scheduled Call</h3>
                    <p class="mb-1">
                        Update your scheduled call details.
                    </p>
                </div>

                <div class="col-xl-8 col-sm-12 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            @include('includes/errors/validation-errors')

                            <form action="{{ route('schedule-calls.update', $scheduleCall) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Scheduled Date & Time</label>
                                            <input type="datetime-local" name="scheduled_at" class="form-control"
                                                value="{{ $scheduleCall->scheduled_at ? $scheduleCall->scheduled_at->format('Y-m-d\TH:i') : '' }}"
                                                required>
                                            @error('scheduled_at')
                                                <div class="mt-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="scheduled" {{ $scheduleCall->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                <option value="completed" {{ $scheduleCall->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $scheduleCall->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="mt-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="4"
                                                placeholder="Add any notes about your scheduled call...">{{ $scheduleCall->notes }}</textarea>
                                            @error('notes')
                                                <div class="mt-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <a href="{{ route('schedule-calls.index') }}" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-success">Update Call</button>
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