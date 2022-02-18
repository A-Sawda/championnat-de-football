<!doctype html>
<html>
    <head>
        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    </head>
    <body>
        <div class="border-bottom shadow-sm p-3 px-md-4 mb-3">
            <div class="container align-items-center d-flex flex-column flex-md-row">
                <h5 class="my-0 mr-md-auto font-weight-normal">@yield('title')</h5>
                <nav class="my-2 my-md-0 mr-md-3">
                    <a class="p-2 text-dark" href="/">Classement</a>
                </nav>
                @if (session()->has('user'))
    <form method="GET" action="{{route('logout')}}">
        <div class="btn-group">
            <a class="btn btn-outline-danger" href="{{route('teams.create')}}">Créer une équipe</a>
            <a class="btn btn-outline-danger" href="{{route('matches.create')}}">Ajouter un match</a>
            <span class="btn btn-primary disabled">{{ session()->get('user')['email'] }}</span>
            <button type="submit" class="btn btn-outline-primary">Déconnexion</a>
        </div>
    </form>
@else
    <a class="btn btn-outline-primary" href="/login">Connexion</a>
@endif
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>