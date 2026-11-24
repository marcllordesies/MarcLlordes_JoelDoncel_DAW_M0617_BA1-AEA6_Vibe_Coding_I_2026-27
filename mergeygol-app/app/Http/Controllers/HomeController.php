<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Team;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $matches = FootballMatch::with([
            'league',
            'localTeam',
            'awayTeam',
            'events.team',
            'events.player',
            'events.relatedPlayer',
        ])
            ->orderBy('start_at')
            ->get();

        $matchesByMatchday = $matches
            ->sortBy([
                ['matchday', 'asc'],
                ['start_at', 'asc'],
            ])
            ->groupBy('matchday');

        $teams = Team::with('players')
            ->where('league_id', $matches->first()?->league_id)
            ->get();

        $teamStats = $teams->map(function (Team $team) use ($matches): array {
            $played = 0;
            $wins = 0;
            $draws = 0;
            $losses = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;

            foreach ($matches->where('status', 'finished') as $match) {
                if ($match->local_team_id === $team->id) {
                    $played++;
                    $goalsFor += $match->local_goals;
                    $goalsAgainst += $match->away_goals;
                    $wins += $match->local_goals > $match->away_goals ? 1 : 0;
                    $draws += $match->local_goals === $match->away_goals ? 1 : 0;
                    $losses += $match->local_goals < $match->away_goals ? 1 : 0;
                } elseif ($match->away_team_id === $team->id) {
                    $played++;
                    $goalsFor += $match->away_goals;
                    $goalsAgainst += $match->local_goals;
                    $wins += $match->away_goals > $match->local_goals ? 1 : 0;
                    $draws += $match->away_goals === $match->local_goals ? 1 : 0;
                    $losses += $match->away_goals < $match->local_goals ? 1 : 0;
                }
            }

            return [
                'team' => $team,
                'played' => $played,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalsFor - $goalsAgainst,
                'points' => ($wins * 3) + $draws,
            ];
        })->sort(function (array $left, array $right): int {
            foreach (['points', 'goal_difference', 'goals_for'] as $field) {
                if ($left[$field] !== $right[$field]) {
                    return $left[$field] > $right[$field] ? -1 : 1;
                }
            }

            return strcmp($left['team']->name, $right['team']->name);
        })->values();

        $topPlayers = $teams
            ->flatMap(fn (Team $team) => $team->players)
            ->sort(function ($left, $right): int {
                if ($left->goals !== $right->goals) {
                    return $left->goals > $right->goals ? -1 : 1;
                }
                if ($left->assists !== $right->assists) {
                    return $left->assists > $right->assists ? -1 : 1;
                }
                return strcmp($left->name, $right->name);
            })
            ->take(10)
            ->values();

        return view('home', [
            'matchesByMatchday' => $matchesByMatchday,
            'teamStats' => $teamStats,
            'topPlayers' => $topPlayers,
        ]);
    }
}
