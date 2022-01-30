@extends('base')

@section('title', 'Création d\'un match')

@section('content')
<form method="POST" action="{{route('matches.store')}}">
@if ($errors->any())
        <div class="alert alert-warning">
        Le match n'a pas pu être ajouté &#9785;
        </div>
@endif

    <div class="form-group">
      <label for="team0">Équipe à domicile</label>
      <select class="form-control" id="team0" name="team0"
      aria-describedby="team0_feedback" 
            class="form-control @error('team0') is-invalid @enderror" required value="{{ old('team0') }}">
      @error('team0')
      <div id="team0_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
          @foreach($teams as $team)
          <option value="$team['id']">{{ $team['name'] }}</option>
          @endforeach
      </select>
    </div>


    <div class="form-group">
      <label for="team1">Équipe à l'extérieur</label>
      <select class="form-control" id="team1" name="team1"
      aria-describedby="team1_feedback" 
            class="form-control @error('team1') is-invalid @enderror" required value="{{ old('team1') }}">
      @error('team1')
      <div id="team1_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
           @foreach($teams as $team)
          <option value="$team['id']">{{ $team['name'] }}</option>
          @endforeach
      </select>
    </div>


    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" class="form-control" id="date" name="date"
      aria-describedby="date_feedback" 
            class="form-control @error('date') is-invalid @enderror" required value="{{ old('date') }}">
      @error('date')
      <div id="date_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="time">Heure</label>
      <input type="time" class="form-control" id="time" name="time"
      aria-describedby="time_feedback" 
            class="form-control @error('time') is-invalid @enderror" required value="{{ old('time') }}">
      @error('time')
      <div id="time_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="score0">Nombre de buts de l'équipe à domicile</label>
      <input type="number" class="form-control" id="score0" name="score0" min="0"
      aria-describedby="score0_feedback" 
            class="form-control @error('score0') is-invalid @enderror" required value="{{ old('score0') }}">
      @error('score0')
      <div id="score0_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="score1">Nombre de buts de l'équipe à l'extérieur</label>
      <input type="number" class="form-control" id="score1" name="score1" min="0" 
      aria-describedby="score1_feedback" 
            class="form-control @error('score1') is-invalid @enderror" required value="{{ old('score1') }}">
      @error('score1')
      <div id="score1_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>

 
             
@endsection