@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-0 mb-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Schedule a Call</h3>
                    <p class="mb-1">
                        Book a call with an instructor or mentor using Calendly.
                    </p>
                </div>

                <div class="col-xl-12">
                    @include('includes/errors/validation-errors')
                    @include('includes/errors/session-message')
                </div>

                <!-- Calendly Booking Section -->
                <div class="col-xl-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 pt-3 bg-gradient-dark">
                            <h6 class="text-white text-capitalize">Book Your Call</h6>
                        </div>
                        <div class="card-body">
                            @if($calendlyUrl)
                                <p class="text-sm text-secondary mb-3">
                                    Click the button below to schedule a call. You'll be redirected to our calendar to select
                                    your preferred date and time.
                                </p>
                                <a href="{{ $calendlyUrl }}" target="_blank" class="btn btn-success btn-lg w-100">
                                    <i class="material-symbols-rounded">calendar_today</i>
                                    &nbsp; Schedule Call with Calendly
                                </a>
                                <p class="text-xs text-secondary mt-3 mb-0">
                                    <em>Note: After booking, your calendar will automatically sync with our system via our
                                        webhook integration.</em>
                                </p>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <strong>Configuration Missing:</strong> Calendly URL has not been configured. Please contact
                                    support.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Scheduled Calls List -->
                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-header pb-0 pt-3 bg-gradient-dark">
                            <h6 class="text-white text-capitalize">Your Scheduled Calls</h6>
                        </div>
                        <div class="card-body pb-2">
                            @if($scheduledCalls->count() > 0)
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Date & Time</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Status</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($scheduledCalls as $call)
                                                <tr>
                                                    <td>
                                                        <p class="text-sm font-weight-normal mb-0">
                                                            @if($call->scheduled_at)
                                                                {{ $call->scheduled_at->format('d M Y, h:i A') }}
                                                            @else
                                                                <span class="badge badge-secondary">Not Set</span>
                                                            @endif
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $call->status === 'scheduled' ? 'success' : ($call->status === 'completed' ? 'info' : 'danger') }}">
                                                            {{ ucfirst($call->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div style="display: flex; gap: 8px;">
                                                            <a class="btn btn-info btn-sm"
                                                                href="{{ route('schedule-calls.show', $call) }}">
                                                                View
                                                            </a>
                                                            @if($call->status === 'scheduled')
                                                                <a class="btn btn-warning btn-sm"
                                                                    href="{{ route('schedule-calls.edit', $call) }}">
                                                                    Edit
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $scheduledCalls->links() }}
                                </div>
                            @else
                                <p class="text-secondary text-sm mb-0">
                                    You haven't scheduled any calls yet. Click "Schedule Call with Calendly" to book one.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <br><br>
            @include('includes/footer-text')
        </div>
    </main>
@endsection