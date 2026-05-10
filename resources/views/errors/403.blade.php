<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unauthorized — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #fff7ed; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { text-align: center; max-width: 400px; padding: 2rem; }
        .emoji { font-size: 5rem; margin-bottom: 1.5rem; }
        h1 { font-family: 'Syne', sans-serif; font-size: 2rem; color: #1f2937; margin-bottom: 0.5rem; }
        p { color: #6b7280; font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.6; }
        a { display: inline-block; background: linear-gradient(to right, #f97316, #f59e0b);
            color: white; font-weight: 600; padding: 0.75rem 2rem; border-radius: 0.75rem;
            text-decoration: none; transition: opacity 0.2s; }
        a:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="card">
        <div class="emoji">🔒</div>
        <h1>Access Denied</h1>
        <p>You don't have permission to view this page. Please make sure you're signed in with the correct account role.</p>
        <a href="{{ url('/') }}">← Go Home</a>
    </div>
</body>
</html>
