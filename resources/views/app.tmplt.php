<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/css/app.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Monda" rel="stylesheet">
        <title>@var('title')</title>
    </head>

    <body>
        <div class="screen">
            <div class="loginbar">
                <i class="fa fa-user-o" aria-hidden="true"></i>
                <a href="/register">registrieren</a> oder <a href="/login">anmelden</a>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="active">Start</a></li>
                    <li><a href="#">Galerie</a></li>
                </ul>
            </nav>

            <div class="content">
                <div class="container">
                    @var('content')
                </div>
            </div>

            <footer>
                <p>&copy; Kruse, Pape &amp; Heumann</p>
                <p>Website im Rahmen des Kurses Webprogrammierung im WS17 an der <a href="https://www.ostfalia.de/">Ostfalia</a>.</p>
            </footer>
        </div>
    </body>
</html>