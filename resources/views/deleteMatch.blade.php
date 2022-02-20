@extends('base')

@section('title', 'Suppression d\'un match')

@section('content')
<form method="POST" action="{{route('deleteMatch.post')}}" >
@csrf
    @if ($errors->any())
        <div class="alert alert-warning">
          Le match n'a pas été supprimé &#9785;
        </div>
    @endif
    <div class="form-group">
      <label for="team0">Équipe à domicile</label>
      <select id="team0" name="team0"
      aria-describedby="team0_feedback" 
            class="form-control @error('team0') is-invalid @enderror" required value="{{ old('team0') }}">
      @error('team0')
      <div id="team0_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
          @foreach($teams as $team)
          <option value="{{$team['id']}}">{{ $team['name'] }}</option>
          @endforeach
      </select>
    </div>


    <div class="form-group">
      <label for="team1">Équipe à l'extérieur</label>
      <select id="team1" name="team1"
      aria-describedby="team1_feedback" 
            class="form-control @error('team1') is-invalid @enderror" required value="{{ old('team1') }}">
      @error('team1')
      <div id="team1_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
           @foreach($teams as $team)
          <option value="{{$team['id']}}">{{ $team['name'] }}</option>
          @endforeach
      </select>
    </div>
    

    <button type="submit" class="btn btn-primary">Supprimer</button>
</form>
@endsection