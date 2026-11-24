{{ $event->minute_label ?? $event->minute }}'
@if($event->type === 'goal')
    @if(str_contains($event->description ?? '', 'gol en pròpia porteria'))
        - gol en pròpia porteria ({{ $event->player?->name ?? 'Jugador no indicat' }})
    @elseif($event->relatedPlayer)
        - asistencia [{{ $event->relatedPlayer->name }}] - gol ({{ $event->player?->name ?? 'Jugador no indicat' }})
    @else
        - gol ({{ $event->player?->name ?? 'Jugador no indicat' }})
    @endif
@elseif($event->type === 'yellow_card')
    - targeta groga ({{ $event->player?->name ?? 'Jugador no indicat' }})
@elseif($event->type === 'red_card')
    - targeta vermella{{ str_contains($event->description ?? '', 'Segona groga') ? ' (segona groga)' : '' }} ({{ $event->player?->name ?? 'Jugador no indicat' }})
@endif
- {{ $event->team->name }}
