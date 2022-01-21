<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Repositories\Data;
use App\Repositories\Ranking;

class Repository
{
    /* Cette méthode appelle la méthode statique unprepared de la classe DB. 
    La fonction PHP file_get_contents prend en argument un nom de fichier, 
    lit le fichier et retourne sous la forme d'une chaîne de caractères le contenu 
    de celui-ci. Par conséquent, la méthode createDatabase
    exécute le script build.sql en étant connectée à la base de données de l'application. */
    function createDatabase(): void 
    {
        DB::unprepared(file_get_contents('database/build.sql'));
    }

    /*ajouter une équipe et retourner son identifiant */
    function insertTeam(array $team): int
    {   
        return array_key_exists("id", $team) ? 
        DB::table('teams')
            ->insertGetId([ 'id' =>$team['id'], 'name' =>$team['name'] ])
        : DB::table('teams')
            ->insertGetId([ 'name' =>$team['name'] ])
        ;
    }

    /*ajouter un match et retourner son identifiant */
    function insertMatch(array $match): int
    {   
        return array_key_exists("id", $match) ? 
        DB::table('matches')
            ->insertGetId([ 'id' =>$match['id'], 
                            'team0' =>$match['team0'],
                            'team1' =>$match['team1'], 
                            'score0' =>$match['score0'],
                            'score1' =>$match['score1'],
                            'date' =>$match['date']
                        ])
        : DB::table('matches')
        ->insertGetId([ 'team0' =>$match['team0'],
                        'team1' =>$match['team1'], 
                        'score0' =>$match['score0'],
                        'score1' =>$match['score1'],
                        'date' =>$match['date']
                    ])
        ;
    }

    function teams(): array
    {
        return DB::table('teams')->orderBy('id')->get()->toArray();
    }

    function matches(): array
    {
        return DB::table('matches')->orderBy('id')->get()->toArray();
    }

    function fillDatabase(): void
    {
        $this->data = new Data();

        foreach($this->data->teams() as $team){
            $this->insertTeam($team);
        }

        foreach($this->data->matches() as $match){
            $this->insertMatch($match);
        }

    }

    function team($teamId): array
    {
        //get et toArray renvoie des tableaux de tableaux
        $teams=DB::table('teams')->where('id', $teamId)->get()->toArray();
        if(empty($teams)){
        throw new Exception("Équipe inconnue"); 
        }
        return $teams[0];
    }

    function match($matchId): array
    {
        //get et toArray renvoie des tableaux de tableaux
        $matches=DB::table('matches')->where('id', $matchId)->get()->toArray();
        if(empty($matches)){
        throw new Exception("Match inconnu"); 
        }
        return $matches[0];
    }

    function insertRanking(array $ranking): int
    {   
        return
        DB::table('ranking')
        ->insertGetId(['rank'=>$ranking['rank'],
                'team_id'=>$ranking['team_id'],
                'match_played_count'=>$ranking['match_played_count'],
                'match_won_count'=>$ranking['match_won_count'],
                'match_lost_count'=>$ranking['match_lost_count'],
                'draw_count'=>$ranking['draw_count'],
                'goal_for_count'=>$ranking['goal_for_count'],
                'goal_against_count'=>$ranking['goal_against_count'],
                'goal_difference'=>$ranking['goal_difference'],
                'points'=>$ranking['points']
                ])
        ;
    }

    function updateRanking(): void
    {
        DB::table('ranking')->delete();
        $teams=$this->teams();
        $matches=$this->matches();
        $this->ranking=new Ranking;
        foreach($this->ranking->sortedRanking($teams,$matches) as $rank){
            $this->insertRanking($rank);
        }
    }

    function sortedRanking(): array
    {
        return 
        DB::table('ranking')
        ->join('teams', 'ranking.team_id', '=', 'teams.id')
        ->orderBy('ranking.rank')
        ->get(['ranking.*', 'teams.name'])
        ->toArray();
    }

    function teamMatches($teamId): array
    {
        return
        DB::table('matches as m')
        ->join('teams as t0', 'm.team0', '=', 't0.id')
        ->join('teams as t1', 'm.team1', '=', 't1.id')
        ->where('m.team0', $teamId)->orWhere('m.team1', $teamId)
        ->orderBy('date')
        ->get(['m.*', 't0.name as name0', 't1.name as name1'])
        ->toArray();
    }
}
