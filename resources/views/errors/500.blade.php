<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error - {{ config('app.name', 'CareerTrack') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { text-align: center; max-width: 480px; }
        .code { font-size: 120px; font-weight: 700; color: #dc2626; line-height: 1; margin-bottom: 8px; }
        .title { font-size: 20px; font-weight: 600; margin-bottom: 8px; }
        .desc { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 32px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: #2563eb; color: #fff; font-size: 14px; font-weight: 500; border-radius: 8px; text-decoration: none; transition: background .2s; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="code">500</div>
        <h1 class="title">Server Error</h1>
        <p class="desc">Something went wrong on our end. Please try again later.</p>
        <a href="{{ route('dashboard') }}" class="btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Back to Dashboard
        </a>
    </div>
</body>
</html>
