@extends('layouts.app')

@section('content')
    @include('includes/sidenav')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('includes/topnav')

    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">
            Check the service, value and bounce rate by location.
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <a href="{{ route('service-calls.index') }}">
            <div class="card">
              <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                  <div>
                    <p class="text-sm mb-0 text-capitalize">Total Service Calls</p>
                    <h4 class="mb-0">{{ $serviceCalls->total }}</h4>
                  </div>
                  <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                    <i class="material-symbols-rounded opacity-10">weekend</i>
                  </div>
                </div>
              </div><br>
              <hr class="dark horizontal my-0">
              <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Completed: {{ $serviceCalls->completedService }}</span> <span class="text-danger font-weight-bolder" style="float:right;">Pending: {{ $serviceCalls->pendingService }}</span></p>
              </div>
            </div>
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 mt-4 mb-4">
          <div class="card ">
            <div class="card-body">
              <h6 class="mb-0 "> Service Calls </h6>
              <!-- <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales. </p> -->
              <div class="pe-2">
                <div class="chart" style="height: 326px;text-align: -webkit-center;">
                  <canvas id="serviceStatusPieChart" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                <!-- <p class="mb-0 text-sm"> updated 4 min ago </p> -->
              </div>
            </div>
          </div>
        </div>
      </div>      
      @include('includes/footer-text')
    </div>
    </main>
@endsection


@section('javascript')
<script>
    const serviceStats = {
        completed: {{ $serviceStats['completedService'] ?? 0 }},
        pending: {{ $serviceStats['pendingService'] ?? 0 }},
    };

    const ctx2 = document.getElementById('serviceStatusPieChart').getContext('2d');
    const pieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                data: [serviceStats.completed, serviceStats.pending],
                backgroundColor: ['#28a745', '#ffc107'],
                borderColor: ['#fff', '#fff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection