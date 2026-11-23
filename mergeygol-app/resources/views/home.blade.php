@extends('layouts.app')

@section('content')
    <h1>Jornada i partits</h1>

    @forelse($matchesByLeague as $leagueName => $leagueMatches)
        <section style="margin-top: 20px;">
            <h2>{{ $leagueName }}</h2>
            <ul style="list-style: none; padding: 0;">
                @foreach($leagueMatches as $match)
                    <li style="display:flex; justify-content:space-between; border:1px solid #ddd; padding:10px; margin-bottom:8px;">
                        <div>
                            <strong>{{ $match->localTeam->code }} {{ $match->local_goals }} - {{ $match->away_goals }} {{ $match->awayTeam->code }}</strong>
                            <div>{{ $match->localTeam->name }} - {{ $match->awayTeam->name }}</div>
                            <div>
                                Estat: {{ $match->status }}
                                @if($match->status === 'live')
                                    ({{ $match->minute }}')
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('matches.show', $match) }}">Veure detall</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @empty
        <p>No hi ha partits disponibles.</p>
    @endforelse
@endsection
