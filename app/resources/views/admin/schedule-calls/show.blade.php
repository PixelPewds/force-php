@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-0 mb-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Call Details</h3>
                    <p class="mb-1">
                        View and manage scheduled call information.
                    </p>
                </div>

                <div class="col-xl-8 col-sm-12 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 pt-3 bg-gradient-dark">
                            <h6 class="text-white text-capitalize">Scheduled Call Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Student Name</label>
                                    <p class="text-sm">{{ $scheduleCall->student->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Student Email</label>
                                    <p class="text-sm">{{ $scheduleCall->student->email }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Scheduled Date & Time</label>
                                    <p class="text-sm">
                                        @if($scheduleCall->scheduled_at)
                                            {{ $scheduleCall->scheduled_at->format('d F Y, h:i A') }}
                                        @else
                                            <span class="badge badge-secondary">Not Set</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Status</label>
                                    <p class="text-sm">
                                        <span
                                            class="badge badge-{{ $scheduleCall->status === 'scheduled' ? 'success' : ($scheduleCall->status === 'completed' ? 'info' : 'danger') }}">
                                            {{ ucfirst($scheduleCall->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="text-sm font-weight-bold">Notes</label>
                                    <p class="text-sm">
                                        @if($scheduleCall->notes)
                                            {{ $scheduleCall->notes }}
                                        @else
                                            <span class="text-secondary">No notes added</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Created Date</label>
                                    <p class="text-sm">{{ $scheduleCall->created_at->format('d F Y, h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-sm font-weight-bold">Last Updated</label>
                                    <p class="text-sm">{{ $scheduleCall->updated_at->format('d F Y, h:i A') }}</p>
                                </div>
                            </div>

                            @if($scheduleCall->calendly_event_url)
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="text-sm font-weight-bold">Calendly Event Link</label>
                                        <p class="text-sm">
                                            <a href="{{ $scheduleCall->calendly_event_url }}" target="_blank"
                                                class="text-primary">
                                                View on Calendly
                                                <i class="material-symbols-rounded"
                                                    style="font-size: 16px; vertical-align: middle;">open_in_new</i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="card mt-4">
                                <div class="card-header pb-0 pt-3 bg-gradient-secondary">
                                    <h6 class="text-white text-capitalize">Update Status</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('schedule-calls.update-status', $scheduleCall) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group input-group-static">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="scheduled" {{ $scheduleCall->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                        <option value="completed" {{ $scheduleCall->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="cancelled" {{ $scheduleCall->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        <option value="pending" {{ $scheduleCall->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group input-group-static">
                                                    <label>Add Notes</label>
                                                    <textarea name="notes" class="form-control"
                                                        rows="4">{{ $scheduleCall->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('schedule-calls.index') }}"
                                                    class="btn btn-secondary">Back</a>
                                                <button type="submit" class="btn btn-success">Update Status</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br><br>
            @include('includes/footer-text')
        </div>
    </main>
@endsection