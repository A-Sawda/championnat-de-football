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
        //var_dump(DB::table('teams')->where('id', $teamId)->get()->toArray());
        return DB::table('teams')->where('id', $teamId)->get()->toArray();
    }
}
