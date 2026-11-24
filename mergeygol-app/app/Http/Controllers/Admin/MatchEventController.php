<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatchEventController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $data = $request->validate([
            'team_id' => ['required', Rule::in([$match->local_team_id, $match->away_team_id])],
            'player_id' => [
                'required',
                Rule::exists('players', 'id')->where('team_id', $request->input('team_id')),
            ],
            'related_player_id' => [
                'exclude_unless:type,goal',
                'nullable',
                'different:player_id',
                Rule::exists('players', 'id')->where('team_id', $request->input('team_id')),
            ],
            'type' => ['required', Rule::in(['goal', 'yellow_card', 'red_card'])],
            'minute' => ['required', 'integer', 'min:1', 'max:130'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        MatchEvent::create([
            'match_id' => $match->id,
            'team_id' => $data['team_id'],
            'player_id' => $data['player_id'],
            'related_player_id' => $data['related_player_id'] ?? null,
            'type' => $data['type'],
            'minute' => $data['minute'],
            'minute_label' => (string) $data['minute'],
            'description' => $data['description'] ?? null,
        ]);

        if ($data['type'] === 'goal') {
            if ((int) $data['team_id'] === (int) $match->local_team_id) {
                $match->increment('local_goals');
            } elseif ((int) $data['team_id'] === (int) $match->away_team_id) {
                $match->increment('away_goals');
            }

            Player::whereKey($data['player_id'])->increment('goals');
            if (! empty($data['related_player_id'])) {
                Player::whereKey($data['related_player_id'])->increment('assists');
            }
        } elseif ($data['type'] === 'yellow_card') {
            Player::whereKey($data['player_id'])->increment('yellow_cards');
        } elseif ($data['type'] === 'red_card') {
            Player::whereKey($data['player_id'])->increment('red_cards');
        }

        return back()->with('status', 'Esdeveniment registrat.');
    }
}
