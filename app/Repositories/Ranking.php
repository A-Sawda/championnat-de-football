<?php
namespace App\Repositories;
class Ranking 
{ 
    function goalDifference(int $goalFor, int $goalAgainst): int 
    {
       return $goalFor - $goalAgainst;
    }

    function points(int $matchWonCount, int $drawMatchCount): int
    {
    return 3*$matchWonCount + $drawMatchCount;
    }

    function teamWinsMatch(int $teamId, array $match): bool
    {
        return
        ($match['team0']==$teamId && $match['score0'] > $match['score1']) 
        ||
        ($match['team1']==$teamId && $match['score1'] > $match['score0']);
        //$key = array_search ($teamId, $match);
        //return $match['score' . substr($key, -1)]>=$match['score0'] && $match['score' . substr($key, -1)]>=$match['score1'];
    }   

function teamLosesMatch(int $teamId, array $match): bool
    {
        return
        ($match['team0']==$teamId && $match['score0'] < $match['score1']) 
        ||
        ($match['team1']==$teamId && $match['score1'] < $match['score0']);
        
    }

function teamDrawsMatch(int $teamId, array $match): bool
    {
        return
        ($match['team0']==$teamId && $match['score0'] == $match['score1']) 
        ||
        ($match['team1']==$teamId && $match['score1'] == $match['score0']);
    }

//Elles doivent retourner respectivement les nombres de buts marqués et encaissés par l'équipe 
//d'identifiant $teamId dans le match décrit par le tableau associatif $match. Si l'équipe ne participe 
//pas au match, elle ne marque ou n'encaisse pas de but, donc les méthodes doivent retourner l'entier 
//zéro. 

function goalForCountDuringAMatch(int $teamId, array $match): int
    {
        return
        ($match['team0']==$teamId || $match['team1']==$teamId) ? 
        $match['team0']==$teamId ? $match['score0'] : $match['score1'] 
        : 0;
    }

function goalAgainstCountDuringAMatch(int $teamId, array $match): int
    {
        return
        ($match['team0']==$teamId || $match['team1']==$teamId) ? 
        $match['team0']==$teamId ? $match['score1'] : $match['score0'] 
        : 0;
    }

//Les méthodes goalForCount et goalAgainstCount doivent retourner les nombres de buts marqués ou 
//encaissés par l'équipe d'identifiant $teamId pour l'ensemble des matchs décrits dans le tableau 
//$matches.
function goalForCount(int $teamId, array $matches): int
{
    $somme=0;
    foreach($matches as $match){
        $somme += $this->goalForCountDuringAMatch($teamId, $match);
    }
    return $somme;
}

function goalAgainstCount(int $teamId, array $matches): int
{
    $somme=0;
    foreach($matches as $match){
        $somme += $this->goalAgainstCountDuringAMatch($teamId, $match);
    }
    return $somme;
}

/*
Ces méthodes prennent un premier argument l'identifiant d'une équipe. Elles vont recevoir en deuxième argument 
un tableau similaire à celui retourné par la méthode matches de la classe Data. Les méthodes matchWonCount, 
matchLostCount et drawMatchCount doivent retourner les nombres de matchs gagnés, perdus et nuls de l'équipe 
d'identifiant $teamId pour l'ensemble des matchs décrits dans le tableau $matches. Implémentez les méthodes 
matchWonCount, matchLostCount et drawMatchCount en utilisant des boucles foreach, des instructions conditionnelles 
et les méthodes teamWinsMatch, teamLosesMatch et teamDrawsMatch.
*/

function matchWonCount(int $teamId, array $matches): int
{
    $count=0;
    foreach($matches as $match){
        if($match['team0']==$teamId && $match['score0']>$match['score1']){
            $count++;
        }
        if($match['team1']==$teamId && $match['score1']>$match['score0']){
            $count++;
        }
    }
    return $count;
}

function matchLostCount(int $teamId, array $matches): int
{
    $count=0;
    foreach($matches as $match){
        if($match['team0']==$teamId && $match['score0']<$match['score1']){
            $count++;
        }
        if($match['team1']==$teamId && $match['score1']<$match['score0']){
            $count++;
        }
    }
    return $count;
}

function drawMatchCount(int $teamId, array $matches): int
{
    $count=0;
    foreach($matches as $match){
        if(($match['team0']==$teamId || $match['team1']==$teamId) && $match['score0']==$match['score1']){
            $count++;
        }
    }
    return $count;
}

/*
Pour le moment, cette méthode retourne un tableau associatif vide (c.-à-d. []). Modifiez le corps de la méthode 
rankingRow(team_id, matches) afin de retourner un tableau associatif contenant les informations qui vont être 
présentes sur la ligne du classement de l'équipe d'identifiant $teamId. Ce tableau associatif doit être construit 
de façon littérale (voir plus haut). Pour cela, vous devez préparer dans des variables, les différentes valeurs 
nécessaires à sa construction. Ces valeurs doivent être calculées à l'aide des fonctions précédemment implémentées. 
Le tableau associatif retourné par la méthode rankingRow doit contenir les informations suivantes :
*/
function matchPlayedCount(int $teamId, array $matches): int
{
    $count=0;
    foreach($matches as $match){
        if($match['team0']==$teamId || $match['team1']==$teamId) {
            $count++;
        }
    }
    return $count;
}

function rankingRow(int $teamId, array $matches): array
{
    $matchPlayedCount= $this->matchPlayedCount($teamId, $matches);
    $matchWonCount= $this->matchWonCount($teamId, $matches);
    $matchLostCount= $this->matchLostCount($teamId, $matches);
    $drawMatchCount= $this->drawMatchCount($teamId, $matches);
    $goalForCount= $this->goalForCount($teamId, $matches);
    $goalAgainstCount= $this->goalAgainstCount($teamId, $matches);
    $goalDifference= $this->goalDifference($goalForCount, $goalAgainstCount);
    $points= $this->points($matchWonCount, $drawMatchCount);
    return [
        'team_id'            => $teamId,
        'match_played_count' => $matchPlayedCount,
        'match_won_count'    => $matchWonCount,
        'match_lost_count'   => $matchLostCount,
        'draw_count'         => $drawMatchCount,
        'goal_for_count'     => $goalForCount,
        'goal_against_count' => $goalAgainstCount,
        'goal_difference'    => $goalDifference,
        'points'             => $points
    ];
}

/*

*/

function unsortedRanking(array $teams, array $matches): array
{
    $array=[];
    foreach($teams as $team){
        $array[]= $this->rankingRow($team['id'], $matches);
    }
    return $array;
}

/*
le nombre de points de l'équipe ;
la différence de but ;
le nombre total de buts marqués.
*/
static function compareRankingRow(array $row1, array $row2): int
{
    if($row1['points']>$row2['points']){
        return -1;
    }
    elseif($row1['points']<$row2['points']){
        return 1;
    }
    elseif($row1['points']==$row2['points']){
        if($row1['goal_difference']>$row2['goal_difference']){
            return -1;
        }
        elseif($row1['goal_difference']<$row2['goal_difference']){
            return 1;
        }
        elseif($row1['goal_difference']==$row2['goal_difference']){
            if($row1['goal_for_count']>$row2['goal_for_count']){
                return -1;
            }
            elseif($row1['goal_for_count']<$row2['goal_for_count']){
                return 1;
            }
            elseif($row1['goal_for_count']==$row2['goal_for_count']){
                return 0;
            }
        }
    }
}

function sortedRanking(array $teams, array $matches): array
{
    $result = $this->unsortedRanking($teams, $matches);
    //usort($a, array("TestObj", "cmp_obj"));
    usort($result, array('App\Repositories\Ranking', 'compareRankingRow'));
    /*$rang=1;
    foreach($result as $team){
        $team['rank']=$rang;
        $rang++;
    }*/
    for ($rank = 1; $rank <= count($result); $rank++) {
        $result[$rank - 1]['rank']=$rank;
      }
    //var_dump($result);
    return $result;
}


}