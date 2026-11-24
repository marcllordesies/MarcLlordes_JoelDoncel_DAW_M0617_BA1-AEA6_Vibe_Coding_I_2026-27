<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MergeYGol</title>
    <style>
        :root { --green:#0b7a53; --navy:#17233c; --line:#dfe5ea; --soft:#f4f7f6; }
        body { margin:0; background:#f7f9fb; color:#1c2733; }
        a { color:var(--green); }
        .jornada-selector { position:relative; display:flex; flex-wrap:wrap; gap:5px; margin-top:10px; padding:6px; background:var(--soft); border:1px solid var(--line); border-radius:10px; }
        .matchday { flex:0 0 auto; margin:0; background:white; border:1px solid var(--line); border-radius:8px; }
        .matchday summary { display:flex; justify-content:center; align-items:center; min-width:42px; padding:8px 6px; cursor:pointer; font-size:12px; font-weight:800; list-style:none; white-space:nowrap; }
        .matchday summary::-webkit-details-marker { display:none; }
        .matchday[open] { background:var(--green); border-color:var(--green); }
        .matchday[open] summary { color:white; }
        .matchday .matches { position:absolute; left:0; right:0; top:calc(100% + 8px); z-index:10; display:grid; gap:10px; padding:12px; background:#f7f9fb; border:1px solid var(--line); border-radius:10px; box-shadow:0 8px 20px rgba(23,35,60,.10); }
        .match-card { display:block; background:white; border:1px solid var(--line); border-radius:10px; padding:14px 16px; text-decoration:none; color:inherit; }
        .match-card:hover { border-color:#91bdaa; box-shadow:0 3px 12px rgba(23,35,60,.06); }
        .match-meta,.match-foot { display:flex; justify-content:space-between; gap:16px; color:#667085; font-size:13px; }
        .match-events { margin:12px 0 0; padding:10px 0 0 20px; border-top:1px solid #edf0f2; color:#475467; font-size:13px; line-height:1.6; }
        .match-events li + li { margin-top:3px; }
        .teams-score { display:grid; grid-template-columns:1fr auto 1fr; align-items:center; gap:16px; margin:12px 0; text-align:center; }
        .teams-score strong:first-child { text-align:right; }
        .teams-score strong:last-child { text-align:left; }
        .score { min-width:70px; font-size:24px; font-weight:800; color:var(--navy); }
        .status { color:#475467; font-weight:700; }
        .status-finished { color:var(--green); }
        .status-live { color:#b42318; }
        .statistics { margin-top:32px; }
        .table-wrap { overflow-x:auto; background:white; border:1px solid var(--line); border-radius:10px; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:10px 12px; border-bottom:1px solid #edf0f2; text-align:left; white-space:nowrap; }
        th { background:var(--soft); color:#475467; font-size:12px; text-transform:uppercase; }
        tr:last-child td { border-bottom:0; }
        @media (max-width:620px) {
            main { padding:16px !important; }
            .match-meta,.match-foot { flex-direction:column; gap:4px; }
        }
    </style>
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
    <script>
        document.querySelectorAll('.jornada-selector details').forEach((details) => {
            details.addEventListener('toggle', () => {
                if (!details.open) return;
                document.querySelectorAll('.jornada-selector details').forEach((other) => {
                    if (other !== details) other.open = false;
                });
            });
        });
    </script>
</body>
</html>
