<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Assessment Report</title>

  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      color: #333;
    }

    .page-break {
      page-break-after: always;
    }

    .header {
      background: #4f46e5;
      color: #fff;
      padding: 10px;
      text-align: center;
    }

    th {
      font-size: 11px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    th,
    td {
      border: 1px solid #ddd;
      /* padding: 5px; */
      font-size: 10px;
      word-wrap: break-word;
      text-align: left;
    }

    .section page-break {
      width: 100%;
      overflow: hidden;
    }

    .section page-break {
      margin: 20px 0;
      width: 100%;
    }

    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .skill-col {
      width: 11%;
    }

    .score-col {
      width: 3%;
      text-align: center;
      font-weight: bold;
    }

    .title {
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 10px;
      color: white;
      background-color: burlywood;
      text-align: center;
      padding: 2px;
      text-transform: uppercase;
      border-radius: 40px;
    }

    .info-table {
      width: 100%;
    }

    .info-table td {
      padding: 6px;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <div class="header">
    <h3>Assessment Report</h3>
    <p>{{ $candidate['name'] }}</p>
  </div>

  <br>
  <!-- BASIC INFO -->
  <div class="section">
    <div class="card">
      <div class="title">Candidate Details</div>
      <table class="info-table">
        <tr>
          <td><strong>Name:</strong></td>
          <td>{{ $candidate['name'] }}</td>
        </tr>
        <tr>
          <td><strong>Email:</strong></td>
          <td>{{ $candidate['email'] }}</td>
        </tr>
        <tr>
          <td><strong>Age:</strong></td>
          <td>{{ $candidate['age'] }}</td>
        </tr>
        <tr>
          <td><strong>Gender:</strong></td>
          <td>{{ $candidate['gender'] }}</td>
        </tr>
        <tr>
          <td><strong>Education:</strong></td>
          <td>{{ $candidate['education'] }}</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="section page-break">
    <div class="title">INTERESTS ASSESSMENT</div>
    <table>
      <thead>
        <tr>
          <th>Question</th>
          <th>Answere</th>
        </tr>
      </thead>
      <tbody>
        @if($resultInterest['interests'])
          @foreach ($resultInterest['interests'] as $interest)
            <tr>
              <td>
                {{ $interest['interestQuestion'] ?? "---" }}
              </td>
              <td>
                {{  $interest['interestAnswere'] ?? "---" }}
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
    <br>
    <table>
      <thead>
        <tr>
          <th>RAISEC</th>
          <th>{{ $raisec['threeLetterCodes']??"NA" }}</th>
        </tr>
      </thead>
    </table>
  </div>
  <br>
  <!-- SKILLS -->
  <div class="section">
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
    <div class="title">Skills Assessment</div>
    <table>
      <thead>
        <tr>
          <th>Creativity</th>
          <th class="score-col"></th>

          <th>Communication</th>
          <th class="score-col"></th>

          <th>Leadership</th>
          <th class="score-col"></th>

          <th>Relationship</th>
          <th class="score-col"></th>

          <th>Analytical</th>
          <th class="score-col"></th>

          <th>Technical</th>
          <th class="score-col"></th>
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
                {{ $item['skillSet'] ?? '' }}
              </td>
              <td class="score-col">
                {{ $item['score'] ?? '' }}
              </td>
            @endforeach
          </tr>
        @endfor
        <tr class="bg-light font-weight-bold">
          @foreach($categories as $cat)
            <td>
            </td>
            <td class="score-col">
              {{ $totals[$cat] ?? 0 }}
            </td>
          @endforeach
        </tr>
      </tbody>
    </table>
  </div>

  <br>

  <!-- VALUES ASSESSMENT -->
  <div class="section">
    <div class="title">VALUES ASSESSMENT</div>
    <table class="table align-items-center mb-0">
      <thead>
        <tr>
          <th>Values</th>
          <th>Score(Max 15)</th>
          <th>Additional Values
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Achievement</td>
          <td>
            {{ $results['Achievement'] ?? 0 }}
          </td>
          <td>
            {{  $results['additional'] ?? "---" }}
          </td>
        </tr>
        <tr>
          <td>Environment</td>
          <td>
            {{ $results['Environment'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Creative</td>
          <td>
            {{ $results['Creative'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Financial Prosperity
          </td>
          <td>
            {{ $results['Financial Prosperity'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Health</td>
          <td>
            {{ $results['Health'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Humility</td>
          <td>
            {{ $results['Humility'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Independence</td>
          <td>
            {{ $results['Independence'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Interdependence</td>
          <td>
            {{ $results['Interdependence'] ?? 0 }}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Objectivity</td>
          <td>
            {{ $results['Objectivity'] ?? 0}}
          </td>
          <td></td>
        </tr>
        <tr>
          <td>Responsibility</td>
          <td>
            {{ $results['Responsibility'] ?? 0 }}
          </td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <br>
  <div class="section">
    <div class="title">LEARNING STYLE ASSESSMENT</div>

    <table class="table mb-0">
      <thead>
        <tr>
          <th>Learning Style</th>
          <th>Score</th>
          <th>Preferences</th>
        </tr>
      </thead>
      <tbody>
        @if($resultLearning)
          <tr>
            <td>
              Activist
            </td>
            <td>
              {{ $resultLearning['Activist'][0] ?? "" }}
            </td>
            <td>
              {{ $resultLearning['Activist'][1] ?? "" }}
            </td>
          </tr>

          <tr>
            <td>
              Reflector
            </td>
            <td>
              {{ $resultLearning['Reflector'][0] ?? "" }}
            </td>
            <td>
              {{ $resultLearning['Reflector'][1] ?? "" }}
            </td>
          </tr>

          <tr>
            <td>
              Theorist
            </td>
            <td>
              {{ $resultLearning['Theorist'][0] ?? "" }}
            </td>
            <td>
              {{ $resultLearning['Theorist'][1] ?? "" }}
            </td>
          </tr>

          <tr>
            <td>
              Pragmatist
            </td>
            <td>
              {{ $resultLearning['Pragmatist'][0] ?? "" }}
            </td>
            <td>
              {{ $resultLearning['Pragmatist'][1] ?? "" }}
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

</body>

</html>