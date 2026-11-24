<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use App\Models\League;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

class LaLigaDataSeeder extends Seeder
{
    private const EVENT_SOURCE = 'LALIGA official 2026/27';

    public function run(): void
    {
        $league = League::where('name', 'LaLiga EA SPORTS 2026/27')->firstOrFail();
        $fixtures = json_decode(
            file_get_contents(database_path('seeders/data/laliga_2026_27.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $teams = Team::where('league_id', $league->id)->get()->keyBy('code');
        $officialMatchKeys = [];

        foreach ($fixtures as $fixture) {
            $home = $teams->get($fixture['home_code']);
            $away = $teams->get($fixture['away_code']);
            if (! $home || ! $away) {
                throw new RuntimeException('An official LaLiga fixture references a team not in the demo league.');
            }
            $officialMatchKeys[] = $fixture['matchday'] . ':' . $home->id . ':' . $away->id;
        }

        $officialMatchKeyLookup = array_fill_keys($officialMatchKeys, true);
        $obsoleteFixtures = FootballMatch::where('league_id', $league->id)
            ->whereBetween('matchday', [1, 16])
            ->with(['localTeam', 'awayTeam'])
            ->get()
            ->filter(fn (FootballMatch $match): bool => ! isset(
                $officialMatchKeyLookup[$match->matchday . ':' . $match->local_team_id . ':' . $match->away_team_id]
            ));

        foreach ($obsoleteFixtures as $match) {
            if ($match->comments()->exists() || $match->predictions()->exists()) {
                throw new RuntimeException('Cannot replace an obsolete demo fixture that already has comments or predictions.');
            }
            $match->delete();
        }

        MatchEvent::where('description', 'like', self::EVENT_SOURCE . '%')->delete();
        MatchEvent::where('description', 'like', 'Gol % (resultat oficial)')->delete();

        foreach ($fixtures as $fixture) {
            $home = $teams->get($fixture['home_code']);
            $away = $teams->get($fixture['away_code']);

            $match = FootballMatch::updateOrCreate(
                [
                    'league_id' => $league->id,
                    'matchday' => $fixture['matchday'],
                    'local_team_id' => $home->id,
                    'away_team_id' => $away->id,
                ],
                [
                    'status' => $fixture['status'],
                    'start_at' => Carbon::parse($fixture['start_at']),
                    'minute' => $fixture['status'] === 'finished' ? 90 : 0,
                    'local_goals' => $fixture['home_score'],
                    'away_goals' => $fixture['away_score'],
                ]
            );
            foreach ($fixture['events'] as $eventData) {
                $playerTeam = $teams->get($eventData['player_team_code']);
                $eventTeam = $teams->get($eventData['team_code']);
                if (! $playerTeam || ! $eventTeam) {
                    throw new RuntimeException('An official LaLiga event references a team not in the demo league.');
                }

                $player = Player::firstOrCreate(
                    ['team_id' => $playerTeam->id, 'name' => $eventData['player_name']],
                    ['position' => null, 'dorsal' => null]
                );
                $relatedPlayer = null;
                if ($eventData['related_player_name']) {
                    $relatedPlayer = Player::firstOrCreate(
                        ['team_id' => $playerTeam->id, 'name' => $eventData['related_player_name']],
                        ['position' => null, 'dorsal' => null]
                    );
                }
                $description = self::EVENT_SOURCE;
                if (! empty($eventData['own_goal'])) {
                    $description .= '; gol en pròpia porteria';
                }
                if (isset($eventData['description'])) {
                    $description .= '; ' . $eventData['description'];
                }

                MatchEvent::create([
                    'match_id' => $match->id,
                    'team_id' => $eventTeam->id,
                    'player_id' => $player->id,
                    'related_player_id' => $relatedPlayer?->id,
                    'type' => $eventData['type'],
                    'minute' => $eventData['minute'],
                    'minute_label' => $eventData['minute_label'],
                    'description' => $description,
                ]);
            }
        }

        $placeholderPlayers = Player::whereIn('team_id', $teams->pluck('id'))
            ->where('name', 'like', 'Plantilla %')
            ->get();
        foreach ($placeholderPlayers as $player) {
            if (
                MatchEvent::where('player_id', $player->id)
                    ->orWhere('related_player_id', $player->id)
                    ->exists()
            ) {
                $player->update(['goals' => 0, 'assists' => 0, 'yellow_cards' => 0, 'red_cards' => 0]);
            } else {
                $player->delete();
            }
        }

        Player::whereIn('team_id', $teams->pluck('id'))
            ->update(['goals' => 0, 'assists' => 0, 'yellow_cards' => 0, 'red_cards' => 0]);
        $this->recalculatePlayerStatistics($league);
    }

    private function recalculatePlayerStatistics(League $league): void
    {
        $events = MatchEvent::whereHas('match', fn ($query) => $query->where('league_id', $league->id))->get();

        foreach ($events as $event) {
            if (
                $event->type === 'goal'
                && $event->player_id
                && ! str_contains($event->description ?? '', 'gol en pròpia porteria')
            ) {
                Player::whereKey($event->player_id)->increment('goals');
            } elseif ($event->type === 'yellow_card' && $event->player_id) {
                Player::whereKey($event->player_id)->increment('yellow_cards');
            } elseif ($event->type === 'red_card' && $event->player_id) {
                Player::whereKey($event->player_id)->increment('red_cards');
            }

            if ($event->type === 'goal' && $event->related_player_id) {
                Player::whereKey($event->related_player_id)->increment('assists');
            }
        }
    }
}
