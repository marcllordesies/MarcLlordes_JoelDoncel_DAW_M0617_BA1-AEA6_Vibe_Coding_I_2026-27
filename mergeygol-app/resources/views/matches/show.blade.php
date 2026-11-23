@extends('layouts.app')

@section('content')
    <h1>{{ $match->localTeam->name }} {{ $match->local_goals }} - {{ $match->away_goals }} {{ $match->awayTeam->name }}</h1>
    <p>Competicio: {{ $match->league->name }} | Estat: {{ $match->status }}</p>

    <section style="margin-top: 16px;">
        <h2>Prediccio 1X2</h2>
        @auth
            @if(! $alreadyPredicted)
                <form method="POST" action="{{ route('predictions.store', $match) }}" style="display:flex; gap:8px;">
                    @csrf
                    <button type="submit" name="choice" value="1">1</button>
                    <button type="submit" name="choice" value="X">X</button>
                    <button type="submit" name="choice" value="2">2</button>
                </form>
            @else
                <p>Ja has votat en aquest partit.</p>
            @endif
        @else
            <p>Inicia sessio per votar.</p>
        @endauth

        <p>Local: {{ $predictionPercentages['1'] }}% | Empat: {{ $predictionPercentages['X'] }}% | Visitant: {{ $predictionPercentages['2'] }}%</p>
    </section>

    <section style="margin-top: 16px;">
        <h2>Timeline</h2>
        <ul>
            @forelse($match->events as $event)
                <li>{{ $event->minute }}' - {{ $event->type }} - {{ $event->team->name }}{{ $event->player ? ' (' . $event->player->name . ')' : '' }}</li>
            @empty
                <li>Sense esdeveniments.</li>
            @endforelse
        </ul>
    </section>

    <section style="margin-top: 16px;">
        <h2>Comentaris</h2>
        @auth
            <form method="POST" action="{{ route('comments.store', $match) }}">
                @csrf
                <textarea name="content" rows="3" style="width:100%;" required></textarea>
                <button type="submit">Publicar</button>
            </form>
        @else
            <p>Inicia sessio per comentar.</p>
        @endauth

        <ul style="padding-left: 18px; margin-top:10px;">
            @forelse($match->comments as $comment)
                <li>
                    <strong>{{ $comment->user->name }}</strong>
                    <small>{{ $comment->created_at->diffForHumans() }}</small>
                    <div>{{ $comment->content }}</div>
                </li>
            @empty
                <li>Encara no hi ha comentaris.</li>
            @endforelse
        </ul>
    </section>

    @auth
        @if(auth()->user()->is_admin)
            <section style="margin-top: 16px; border-top:1px solid #ddd; padding-top:12px;">
                <h2>Admin: afegir esdeveniment</h2>
                <form method="POST" action="{{ route('admin.matches.events.store', $match) }}" style="display:grid; gap:8px; max-width:480px;">
                    @csrf
                    <select name="team_id" required>
                        <option value="">Equip</option>
                        <option value="{{ $match->localTeam->id }}">{{ $match->localTeam->name }}</option>
                        <option value="{{ $match->awayTeam->id }}">{{ $match->awayTeam->name }}</option>
                    </select>
                    <input type="number" name="minute" min="1" max="130" placeholder="Minut" required>
                    <input type="text" name="type" placeholder="Tipus (goal, yellow_card...)" required>
                    <button type="submit">Registrar</button>
                </form>
            </section>
        @endif
    @endauth
@endsection
