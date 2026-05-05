@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-0">
                    <h3 class="mb-0 h4 font-weight-bolder">Scheduled Calls</h3>
                    <p class="mb-1">
                        Manage and monitor all student scheduled calls.
                    </p>
                </div>

                <div class="col-12">
                    @include('includes/errors/session-message')
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                                <h6 class="text-white text-capitalize ps-3">Filter Scheduled Calls</h6>
                            </div>
                        </div>
                        <div class="card-body pb-2">
                            <form method="GET" action="{{ route('schedule-calls.index') }}" class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <div class="input-group input-group-static">
                                        <label>Student</label>
                                        <select name="student_id" class="form-control">
                                            <option value="">All Students</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} ({{ $student->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-static">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-static">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" class="form-control"
                                            value="{{ request('from_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group input-group-static">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" class="form-control"
                                            value="{{ request('to_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                    <a href="{{ route('schedule-calls.index') }}"
                                        class="btn btn-secondary w-100 mt-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                                <h6 class="text-white text-capitalize ps-3">Scheduled Calls List</h6>
                            </div>
                        </div>
                        <div class="card-body pb-2">
                            @if($scheduledCalls->count() > 0)
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Student</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Email</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Scheduled Date</th>
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
                                                        <p class="text-sm font-weight-normal mb-0">{{ $call->student->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-normal mb-0">{{ $call->student->email }}</p>
                                                    </td>
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
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('schedule-calls.show', $call) }}">
                                                            View
                                                        </a>
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
                                <div class="alert alert-info" role="alert">
                                    No scheduled calls found.
                                </div>
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