@extends('base')

@section('title', 'Mot de passe')

@section('content')
<form method="POST" action="{{route('changePassword.post')}}" >
@csrf
    @if ($errors->any())
        <div class="alert alert-warning">
          Votre mot de pase n'a pas été changé &#9785;
        </div>
    @endif
    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value="{{old('email')}}"
             aria-describedby="email_feedback" class="form-control @error('email') is-invalid @enderror"> 
      @error('email')
      <div id="email_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <div class="form-group">
      <label for="oldPassword">Mot de passe</label>
      <input type="password" id="oldPassword" name="oldPassword" value="{{old('oldPassword')}}"
             aria-describedby="oldPassword_feedback" class="form-control @error('oldPassword') is-invalid @enderror">  
      @error('oldPassword')
      <div id="oldPassword_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <div class="form-group">
      <label for="newPassword">Mot de passe</label>
      <input type="password" id="newPassword" name="newPassword" value="{{old('newPassword')}}"
             aria-describedby="newPassword_feedback" class="form-control @error('newPassword') is-invalid @enderror">  
      @error('password')
      <div id="newPassword_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
</form>
@endsection