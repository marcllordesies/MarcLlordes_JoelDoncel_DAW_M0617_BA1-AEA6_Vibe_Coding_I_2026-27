<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchEventController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $data = $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'player_id' => ['nullable', 'exists:players,id'],
            'type' => ['required', 'string', 'max:60'],
            'minute' => ['required', 'integer', 'min:1', 'max:130'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        MatchEvent::create([
            'match_id' => $match->id,
            'team_id' => $data['team_id'],
            'player_id' => $data['player_id'] ?? null,
            'related_player_id' => null,
            'type' => $data['type'],
            'minute' => $data['minute'],
            'description' => $data['description'] ?? null,
        ]);

        if ($data['type'] === 'goal') {
            if ((int) $data['team_id'] === (int) $match->local_team_id) {
                $match->increment('local_goals');
            } elseif ((int) $data['team_id'] === (int) $match->away_team_id) {
                $match->increment('away_goals');
            }

            if (! empty($data['player_id'])) {
                Player::whereKey($data['player_id'])->increment('goals');
            }
        }

        return back()->with('status', 'Esdeveniment registrat.');
    }
}
