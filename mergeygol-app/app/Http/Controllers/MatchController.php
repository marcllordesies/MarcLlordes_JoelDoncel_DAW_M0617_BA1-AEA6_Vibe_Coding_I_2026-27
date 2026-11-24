<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function show(FootballMatch $match): View
    {
        $match->load([
            'league',
            'localTeam.players',
            'awayTeam.players',
            'events.team',
            'events.player',
            'events.relatedPlayer',
            'comments.user',
            'predictions',
        ]);

        $totals = [
            '1' => $match->predictions->where('choice', '1')->count(),
            'X' => $match->predictions->where('choice', 'X')->count(),
            '2' => $match->predictions->where('choice', '2')->count(),
        ];

        $votes = max(array_sum($totals), 1);

        $percentages = [
            '1' => round(($totals['1'] / $votes) * 100),
            'X' => round(($totals['X'] / $votes) * 100),
            '2' => round(($totals['2'] / $votes) * 100),
        ];

        return view('matches.show', [
            'match' => $match,
            'predictionPercentages' => $percentages,
            'alreadyPredicted' => auth()->check() && $match->predictions->contains('user_id', auth()->id()),
        ]);
    }
}
