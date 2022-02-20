@extends('base')

@section('title')
Création d'une équipe
@endsection

@section('content')
<form method="POST" action="{{route('teams.store')}}">
@csrf
    @if ($errors->any())
        <div class="alert alert-warning">
            L'équipe n'a pas pu être ajoutée &#9785;
        </div>
    @endif
    <div class="form-group">
      <label for="team_name">Nom de l'équipe</label>
      <input type="text" id="team_name" name="team_name"  
             aria-describedby="team_name_feedback" 
            class="form-control @error('team_name') is-invalid @enderror"
            minlength="3" maxlength="20" required value="{{ old('team_name') }}">
      @error('team_name')
      <div id="team_name_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>
@endsection