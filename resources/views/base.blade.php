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
                <a class="btn btn-outline-primary" href="/login">Connexion</a>
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>