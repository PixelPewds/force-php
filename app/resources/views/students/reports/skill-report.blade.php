@extends('layouts.app')

@section('content')
  @include('includes/sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('includes/topnav')
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-0">
          <h3 class="mb-0 h4 font-weight-bolder">Report View</h3>
          <p class="mb-1">
            Report data here.
          </p>
        </div>

        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Candidate Details</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-2">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Full Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Age</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gender</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Education</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($candidateInfo)
                      <tr>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $candidateInfo[1] }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $candidateInfo[0] }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $candidateInfo[2] }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $candidateInfo[3] }}</p>
                        </td>
                        <td>
                          <p class="text-sm font-weight-normal mb-0">{{ $candidateInfo[4] }}</p>
                        </td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-warning shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Skill Report</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              <div class="table-responsive p-0">
                <table class="table table-striped align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skill Category</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skill Set</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Proficiency</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Importance</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($finalSkillReport as $report)
                      @if(isset($report['skillSet']) && $report['skillSet'])
                        <tr>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $report['skillCatergory'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $report['skillSet'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $report['importance'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $report['skillCatergory'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $report['score'] }}</p>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">Report View</h6>
              </div>
            </div>
            <div class="card-body  pb-2">
              @php
                $categories = ['Creativity', 'Communication', 'Leadership', 'Relationship', 'Analytical', 'Technical'];

                $maxRows = collect($categories)
                  ->map(fn($cat) => $grouped[$cat] ?? collect())
                  ->map->count()
                  ->max();

                $totals = [];

                foreach ($categories as $cat) {
                  $totals[$cat] = collect($grouped[$cat] ?? [])
                    ->sum('score');
                }
              @endphp
              <div class="table-responsive p-0">
                <table class="table table-bordered align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Creativity</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>

                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Communication</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>

                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leadership</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>

                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Relationship</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>

                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Analytical</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>

                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Technical</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($i = 0; $i < $maxRows; $i++)
                      <tr class="align-items-center">
                        @foreach($categories as $cat)
                          @php
                            $item = $grouped[$cat][$i] ?? null;
                          @endphp
                          <td>
                            <p class="text-sm font-weight-normal mb-0">{{ $item['skillSet'] ?? '' }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-normal text-center mb-0"> {{ $item['score'] ?? '' }}</p>
                          </td>
                        @endforeach
                      </tr>
                    @endfor
                    <tr class="bg-light font-weight-bold">

                      @foreach($categories as $cat)
                        <td>
                          <!-- <p class="text-sm font-weight-normal text-center mb-0"> Total</p> -->
                        </td>
                        <td>
                          <p class="text-sm text-center mb-0 font-weight-bold"> {{ $totals[$cat] ?? 0 }}
                          </p>
                        </td>
                      @endforeach

                    </tr>
                  </tbody>
                </table>
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
@section('javascript')
  <script>

  </script>
@endsection