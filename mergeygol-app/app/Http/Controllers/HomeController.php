<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $matches = FootballMatch::with(['league', 'localTeam', 'awayTeam'])
            ->orderBy('start_at')
            ->get()
            ->groupBy(fn ($match) => $match->league->name);

        return view('home', [
            'matchesByLeague' => $matches,
        ]);
    }
}
