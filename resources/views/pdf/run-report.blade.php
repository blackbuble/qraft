<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .meta {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .meta p {
            margin: 5px 0;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
        }

        .status.failed {
            color: red;
        }

        .status.success,
        .status.completed {
            color: green;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .screenshot {
            max-width: 100%;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        .analysis {
            background: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>QRAFT Test Report</h1>
        <p>Run #{{ $run->id }}</p>
    </div>

    <div class="meta">
        <p><strong>Project:</strong> {{ $run->project->name }}</p>
        <p><strong>Scenario:</strong> {{ $run->testScenario->title ?? 'Ad-hoc Run' }}</p>
        <p><strong>Date:</strong> {{ $run->created_at->format('Y-m-d H:i:s') }}</p>
        <p><strong>Status:</strong> <span class="status {{ $run->status }}">{{ $run->status }}</span></p>
    </div>

    @if($run->result && isset($run->result['ai_analysis']))
        <div class="section">
            <h2>AI Analysis</h2>
            <div class="analysis">
                {!! \Illuminate\Support\Str::markdown($run->result['ai_analysis']) !!}
            </div>
        </div>
    @endif

    <div class="section">
        <h2>Execution Logs</h2>
        <ul>
            @foreach($run->result['logs'] ?? [] as $log)
                <li>{{ $log }}</li>
            @endforeach
        </ul>
    </div>

    @if($run->result && isset($run->result['screenshot']))
        <div class="section">
            <h2>Visual Evidence</h2>
            <img src="data:image/jpeg;base64,{{ $run->result['screenshot'] }}" class="screenshot">
        </div>
    @endif
</body>

</html>