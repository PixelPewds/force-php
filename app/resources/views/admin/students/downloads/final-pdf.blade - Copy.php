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

    .header {
      background: #4f46e5;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .section {
      margin: 20px 0;
    }

    .card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #4f46e5;
    }

    .info-table {
      width: 100%;
    }

    .info-table td {
      padding: 6px;
    }

    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      color: #fff;
      font-size: 12px;
    }

    .high {
      background: #16a34a;
    }

    .medium {
      background: #f59e0b;
    }

    .low {
      background: #dc2626;
    }

    .skill-box {
      margin-bottom: 10px;
    }

    .skill-name {
      font-weight: bold;
    }

    .progress {
      height: 6px;
      background: #eee;
      border-radius: 4px;
      margin-top: 5px;
    }

    .progress-bar {
      height: 6px;
      background: #4f46e5;
      border-radius: 4px;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <div class="header">
    <h2>Assessment Report</h2>
    <p>{{ $name }}</p>
  </div>

  <!-- BASIC INFO -->
  <div class="section">
    <div class="card">
      <div class="title">Candidate Details</div>

      <table class="info-table">
        <tr>
          <td><strong>Name:</strong></td>
          <td>{{ $name }}</td>
        </tr>
        <tr>
          <td><strong>Email:</strong></td>
          <td>{{ $email }}</td>
        </tr>
        <tr>
          <td><strong>Age:</strong></td>
          <td>{{ $age }}</td>
        </tr>
        <tr>
          <td><strong>Gender:</strong></td>
          <td>{{ $gender }}</td>
        </tr>
        <tr>
          <td><strong>Education:</strong></td>
          <td>{{ $education }}</td>
        </tr>
      </table>
    </div>
  </div>

  <!-- SKILLS -->
  <div class="section">
    <div class="title">Skills Assessment</div>

    @foreach($skills as $category => $items)
      <div class="card">
        <div class="title">{{ $category }}</div>

        @foreach($items as $skill)
          <div class="skill-box">
            <div class="skill-name">
              {{ $skill['skill'] }}
            </div>

            <div>
              Proficiency:
              <span class="badge 
                                    @if(str_contains($skill['proficiency'], 'High')) high
                                    @elseif(str_contains($skill['proficiency'], 'Moderate')) medium
                                    @else low
                                    @endif">
                {{ $skill['proficiency'] }}
              </span>
            </div>

            <div class="progress">
              <div class="progress-bar" style="width: {{ ($skill['score'] / 12) * 100 }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    @endforeach
  </div>

</body>

</html>