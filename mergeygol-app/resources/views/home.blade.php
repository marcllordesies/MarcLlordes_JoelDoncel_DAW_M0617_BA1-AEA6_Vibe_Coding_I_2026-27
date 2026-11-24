@extends('layouts.app')

@section('content')
    <h1>Partits per jornades</h1>

    <div class="jornada-selector">
        @forelse($matchesByMatchday as $matchday => $matchdayMatches)
            <details class="matchday">
                <summary title="{{ $matchdayMatches->count() }} partits">
                    J{{ $matchday }}
                </summary>
                <div class="matches">
                    @foreach($matchdayMatches as $match)
                        @php
                            $statusLabels = [
                                'finished' => 'Finalitzat',
                                'live' => 'En joc',
                                'scheduled' => 'No iniciat',
                            ];
                            $statusLabel = $statusLabels[$match->status] ?? 'No iniciat';
                        @endphp
                        <a class="match-card" href="{{ route('matches.show', $match) }}">
                            <div class="match-meta">
                                <span>{{ $match->league->name }}</span>
                                <span>{{ $match->start_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="teams-score">
                                <strong>{{ $match->localTeam->code }}</strong>
                                <span class="score">{{ $match->status === 'scheduled' ? '-' : $match->local_goals }} - {{ $match->status === 'scheduled' ? '-' : $match->away_goals }}</span>
                                <strong>{{ $match->awayTeam->code }}</strong>
                            </div>
                            <div class="match-foot">
                                <span>{{ $match->localTeam->name }} - {{ $match->awayTeam->name }}</span>
                                <span class="status status-{{ $match->status }}">
                                    Estat: {{ $statusLabel }}
                                    @if($match->status === 'live')
                                        ({{ $match->minute }}')
                                    @endif
                                </span>
                            </div>
                            @php
                                $matchEvents = $match->events->whereIn('type', ['goal', 'yellow_card', 'red_card']);
                            @endphp
                            @if($matchEvents->isNotEmpty())
                                <ul class="match-events">
                                    @foreach($matchEvents as $event)
                                        <li>
                                            @include('matches.event-label', ['event' => $event])
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </a>
                    @endforeach
                </div>
            </details>
        @empty
            <p>No hi ha partits disponibles.</p>
        @endforelse
    </div>

    <section class="statistics">
        <h2>Classificació i estadístiques dels equips</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Equip</th><th>PJ</th><th>PG</th><th>PE</th><th>PP</th><th>GF</th><th>GC</th><th>DG</th><th>Punts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamStats as $index => $stats)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $stats['team']->name }}</strong></td>
                            <td>{{ $stats['played'] }}</td>
                            <td>{{ $stats['wins'] }}</td>
                            <td>{{ $stats['draws'] }}</td>
                            <td>{{ $stats['losses'] }}</td>
                            <td>{{ $stats['goals_for'] }}</td>
                            <td>{{ $stats['goals_against'] }}</td>
                            <td>{{ $stats['goal_difference'] }}</td>
                            <td><strong>{{ $stats['points'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="statistics">
        <h2>Estadístiques generals dels jugadors</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Jugador</th><th>Equip</th><th>Posició</th><th>Gols</th><th>Assist.</th><th>Targetes grogues</th><th>Targetes vermelles</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPlayers as $player)
                        <tr>
                            <td><strong>{{ $player->name }}</strong></td>
                            <td>{{ $player->team?->name }}</td>
                            <td>{{ $player->position }}</td>
                            <td>{{ $player->goals }}</td>
                            <td>{{ $player->assists }}</td>
                            <td>{{ $player->yellow_cards }}</td>
                            <td>{{ $player->red_cards }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No hi ha estadístiques de jugadors disponibles.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
