<html>
<head>
    <title>500 - Internal Server Error</title>
</head>
<body>
    <h1>500 - Internal Server Error!</h1>
    <div class="error" style="text-align: left;">
        <?php if (config('app.debug')) {
            echo $error;
        }
        ?>
    </div>
</body>
</html>