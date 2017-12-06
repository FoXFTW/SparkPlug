<html>
<head>
    <title>500 - Interner Fehler</title>
</head>
<body>
<div class="container">
    <div class="contentbox jumbotron">
        <h1>Interner Serverfehler!</h1>
        <div class="error" style="text-align: left;">
            <?php if (config('app.debug')) {
                echo $error;
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>