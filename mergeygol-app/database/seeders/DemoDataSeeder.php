<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use App\Models\League;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $league = League::updateOrCreate(['name' => 'LaLiga EA SPORTS 2026/27']);

        $teams = [
            'Alavés' => 'ALA', 'Athletic' => 'ATH', 'Atlético' => 'ATM',
            'Barcelona' => 'BAR', 'Betis' => 'BET', 'Celta' => 'CEL',
            'Deportivo' => 'DEP', 'Elche' => 'ELC', 'Espanyol' => 'ESP',
            'Getafe' => 'GET', 'Levante' => 'LEV', 'Málaga' => 'MGA',
            'Osasuna' => 'OSA', 'Racing' => 'RAC', 'Rayo' => 'RAY',
            'Real Madrid' => 'RMA', 'R. Sociedad' => 'RSO', 'Sevilla' => 'SEV',
            'Valencia' => 'VAL', 'Villarreal' => 'VIL',
        ];

        $teamModels = [];
        foreach ($teams as $name => $code) {
            $teamModels[$name] = Team::updateOrCreate(
                ['league_id' => $league->id, 'code' => $code],
                ['name' => $name, 'stadium' => null]
            );
            Player::firstOrCreate(
                ['team_id' => $teamModels[$name]->id, 'name' => 'Plantilla ' . $code],
                ['position' => 'Davanter', 'dorsal' => 9]
            );
        }

        // Resultats oficials publicats per LALIGA fins al 22/11/2026.
        $results = [
            1 => [['Alavés','Getafe',3,0],['Sevilla','Rayo',2,1],['Racing','Villarreal',2,2],['Espanyol','Levante',3,0],['Deportivo','Elche',1,1],['Atlético','Málaga',2,0],['Valencia','Betis',0,1],['Real Madrid','R. Sociedad',4,1],['Celta','Osasuna',1,2],['Barcelona','Athletic',2,0]],
            2 => [['Rayo','Alavés',1,1],['Betis','R. Sociedad',1,0],['Athletic','Sevilla',1,3],['Valencia','Celta',0,0],['Espanyol','Real Madrid',1,2],['Atlético','Villarreal',2,2],['Getafe','Racing',1,0],['Elche','Barcelona',0,5],['Osasuna','Levante',0,0],['Málaga','Deportivo',1,1]],
            3 => [['Racing','Elche',3,2],['Alavés','Villarreal',1,0],['Levante','Betis',5,2],['R. Sociedad','Espanyol',2,1],['Sevilla','Atlético',1,3],['Real Madrid','Málaga',4,0],['Deportivo','Valencia',3,1],['Celta','Athletic',0,2],['Osasuna','Getafe',1,0],['Barcelona','Rayo',5,2]],
            4 => [['Betis','Real Madrid',1,0],['Athletic','Atlético',3,0],['Rayo','Racing',3,2],['Villarreal','Deportivo',2,3],['Valencia','Barcelona',0,5],['Alavés','Osasuna',5,2],['Málaga','Levante',0,0],['Espanyol','Sevilla',1,1],['Getafe','Celta',1,1],['Elche','R. Sociedad',2,3]],
            5 => [['Sevilla','Valencia',1,0],['Racing','Alavés',2,1],['Osasuna','Espanyol',0,2],['Athletic','Elche',1,1],['Real Madrid','Rayo',4,1],['Celta','Málaga',1,1],['Levante','Barcelona',2,4],['Getafe','Deportivo',1,1],['R. Sociedad','Atlético',0,3],['Villarreal','Betis',1,2]],
            6 => [['R. Sociedad','Celta',0,0],['Rayo','Espanyol',2,1],['Alavés','Valencia',0,1],['Elche','Real Madrid',2,3],['Atlético','Osasuna',4,0],['Deportivo','Sevilla',0,1],['Barcelona','Racing',7,2],['Betis','Getafe',1,0],['Málaga','Villarreal',1,3],['Levante','Athletic',3,1]],
            7 => [['Espanyol','Elche',1,3],['Osasuna','Rayo',1,1],['Athletic','Alavés',0,0],['Celta','Racing',5,0],['Sevilla','Barcelona',1,3],['Getafe','Málaga',1,0],['Atlético','Real Madrid',2,1],['Deportivo','Betis',1,1],['Villarreal','Levante',3,1],['Valencia','R. Sociedad',2,3]],
            8 => [['Málaga','Espanyol',1,1],['Alavés','Atlético',1,2],['Barcelona','Getafe',3,0],['Real Madrid','Villarreal',1,0],['Elche','Celta',0,0],['R. Sociedad','Deportivo',3,2],['Betis','Osasuna',5,2],['Racing','Valencia',2,2],['Levante','Sevilla',3,3],['Rayo','Athletic',0,0]],
            9 => [['Deportivo','Levante',1,1],['Espanyol','Atlético',0,1],['Villarreal','Elche',0,0],['Betis','Barcelona',2,5],['Valencia','Athletic',0,0],['Osasuna','Racing',2,0],['Celta','Alavés',2,1],['Málaga','R. Sociedad',2,5],['Real Madrid','Sevilla',4,1],['Getafe','Rayo',1,1]],
            10 => [['Alavés','Málaga',1,1],['Rayo','Elche',0,3],['Racing','Espanyol',2,1],['Valencia','Villarreal',1,2],['Atlético','Deportivo',1,0],['Athletic','Getafe',4,0],['Celta','Betis',0,0],['R. Sociedad','Levante',2,1],['Barcelona','Real Madrid',1,2],['Sevilla','Osasuna',2,0]],
            11 => [['Villarreal','Espanyol',0,1],['Levante','Atlético',0,2],['Rayo','Celta',3,0],['Betis','Málaga',2,1],['Barcelona','Alavés',6,1],['Getafe','Sevilla',3,1],['Racing','Real Madrid',0,3],['Elche','Valencia',0,1],['Athletic','R. Sociedad',2,1],['Deportivo','Osasuna',2,1]],
            12 => [['Sevilla','Alavés',0,0],['Málaga','Racing',1,2],['Espanyol','Deportivo',0,0],['Osasuna','Athletic',1,1],['Valencia','Real Madrid',0,3],['Elche','Betis',0,2],['R. Sociedad','Rayo',1,0],['Villarreal','Getafe',2,0],['Celta','Levante',0,0],['Atlético','Barcelona',1,2]],
            13 => [['Athletic','Espanyol',0,1],['Alavés','Deportivo',3,1],['Rayo','Valencia',0,1],['Sevilla','Betis',1,1],['Real Madrid','Celta',2,1],['Barcelona','Villarreal',3,2],['Racing','R. Sociedad',3,2],['Osasuna','Málaga',1,0],['Getafe','Atlético',0,3]],
        ];

        foreach ($results as $matchday => $matches) {
            foreach ($matches as [$home, $away, $homeGoals, $awayGoals]) {
                $this->storeMatch($league->id, $teamModels, $matchday, $home, $away, $homeGoals, $awayGoals);
            }
        }

        // La jornada 13 seguia en joc el 23/11/2026: no s'inventa el resultat.
        $this->storeMatch($league->id, $teamModels, 13, 'Levante', 'Elche', 0, 0, false);
    }

    private function storeMatch(int $leagueId, array $teams, int $matchday, string $home, string $away, int $homeGoals, int $awayGoals, bool $finished = true): void
    {
        $matchdayDates = [
            1 => '2026-08-15', 2 => '2026-08-20', 3 => '2026-08-28',
            4 => '2026-09-04', 5 => '2026-09-11', 6 => '2026-09-15',
            7 => '2026-09-18', 8 => '2026-10-09', 9 => '2026-10-16',
            10 => '2026-10-23', 11 => '2026-10-30', 12 => '2026-11-06',
            13 => '2026-11-20',
        ];

        $match = FootballMatch::updateOrCreate(
            [
                'league_id' => $leagueId,
                'matchday' => $matchday,
                'local_team_id' => $teams[$home]->id,
                'away_team_id' => $teams[$away]->id,
            ],
            [
                'status' => $finished ? 'finished' : 'scheduled',
                'start_at' => Carbon::parse($matchdayDates[$matchday])->setTime(21, 0),
                'minute' => $finished ? 90 : 0,
                'local_goals' => $finished ? $homeGoals : 0,
                'away_goals' => $finished ? $awayGoals : 0,
            ]
        );

        if (! $finished) {
            return;
        }

        foreach (array_merge(
            array_fill(0, $homeGoals, [$teams[$home], 'local']),
            array_fill(0, $awayGoals, [$teams[$away], 'away'])
        ) as $index => [$team, $side]) {
            MatchEvent::firstOrCreate(
                ['match_id' => $match->id, 'team_id' => $team->id, 'type' => 'goal', 'minute' => 10 + ($index * 13)],
                ['description' => 'Gol ' . ($side === 'local' ? 'local' : 'visitant') . ' (resultat oficial)']
            );
        }
    }
}
