@extends('layouts.app')

@section('content')
    @include('includes/sidenav')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('includes/topnav')
        <div class="container-fluid py-4">
            <div class="row">
                <div class="ms-0 mb-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Activity Logs</h3>
                    <p class="mb-1">
                        Logs.
                    </p>
                </div>

                <div class="col-md-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                                <h6 class="text-white text-capitalize ps-3">Logs</h6>
                            </div>
                        </div>

                        <div class="card-body  pb-2">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Id</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Description</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">User</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $key => $activity)
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">#{{ $key + 10303 }}</p>
                                                </td>
                                                <td class="wrap-text">
                                                    <p class="text-sm font-weight-bold mb-0 wrap-text">
                                                        {{ $activity->description }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm mb-0">{{ $activity->causer->name ?? 'System' }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $activity->causer->email ?? '' }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm">{{ $activity->created_at->format('d M Y h:i A') }}</span>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $activity->created_at->diffForHumans() }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mx-4 my-3">
                                <!-- {{ $activities->links() }} -->
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    {{ $activities->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('includes/footer-text')
                </div>
            </div>
        </div>

    </main>
@endsection

@section('javascript')
    <script>
        var table = new DataTable('#activity-datatable', {
            scrollX: false,
            "lengthChange": false,
            paging: false,
            "bInfo": false,
            layout: {
                topStart: {
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    buttons: ['excel', 'pdf']
                }
            }
        });
        table.order([]).draw();
    </script>
@endsection