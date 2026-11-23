<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MergeYGol</title>
</head>
<body>
    <main style="max-width: 960px; margin: 0 auto; padding: 24px; font-family: Arial, sans-serif;">
        <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="font-size: 28px; font-weight: 700; text-decoration: none; color: #111;">MergeYGol</a>
        </header>

        @if(session('status'))
            <div style="background:#e6ffed; border:1px solid #9be9a8; padding:10px; margin-bottom: 14px;">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
