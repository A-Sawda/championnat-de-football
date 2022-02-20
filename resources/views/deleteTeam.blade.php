@extends('base')

@section('title', 'Suppression d\'une équipe')

@section('content')
<form method="POST" action="{{route('deleteTeam.post')}}" >
@csrf
    @if ($errors->any())
        <div class="alert alert-warning">
          L'équipe n'a pas été supprimée &#9785;
        </div>
    @endif
    <div class="form-group">
      <label for="team0">Nom de l'équipe</label>
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
    
    <button type="submit" class="btn btn-primary">Supprimer</button>
</form>
@endsection