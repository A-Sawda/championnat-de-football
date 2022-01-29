@extends('base')

@section('title')
Matchs de l'équipe
@endsection

@section('content')

<table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                <th>N°</th>
                <th>Équipe</th>
                <th>MJ</th>
                <th>G</th>
                <th>N</th>
                <th>P</th>
                <th>BP</th>
                <th>BC</th>
                <th>DB</th>
                <th>PTS</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $rankingRow['rank'] }}</td>
                    <td>{{ $rankingRow['name'] }}</a></td>
                    <td>{{ $rankingRow['match_played_count'] }}</td>
                    <td>{{ $rankingRow['match_won_count'] }}</td>
                    <td>{{ $rankingRow['draw_count'] }}</td>
                    <td>{{ $rankingRow['match_lost_count'] }}</td>
                    <td>{{ $rankingRow['goal_for_count'] }}</td>
                    <td>{{ $rankingRow['goal_against_count'] }}</td>
                    <td>{{ $rankingRow['goal_difference'] }}</td>
                    <td>{{ $rankingRow['points'] }}</td>
                </tr>
            </tbody>
            </table>


   <table class="table table-striped">
                <tbody>
            @foreach ($teamMatches as $teamMatch)
                <tr>
                    <td>{{ $teamMatch['date'] }}</td>
                    <td><a href="{{route('teams.show', ['teamId'=>$teamMatch['team0']])}}">{{ $teamMatch['name0'] }}</a></td> 
                    <td>{{ $teamMatch['score0'] }}</td>
                    <td>{{ $teamMatch['score1'] }}</td>
                    <td><a href="{{route('teams.show', ['teamId'=>$teamMatch['team1']])}}">{{ $teamMatch['name1'] }}</a></td>
                </tr>
            @endforeach
            </tbody>
            </table>
        @endsection