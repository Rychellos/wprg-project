<?php
function head($title = "", $stylesheets = [])
{
    echo <<<EOD
        <head>
        <base href="/wprg-project/" />
            <script src="scripts/theme_apply.js"></script>
            <title>$title</title>
            <link rel="stylesheet" href="styles/global.css">
            <script
                src="bootstrap/dist/js/bootstrap.bundle.min.js"
            ></script>
    EOD;

    for ($i = 0; $i < sizeof($stylesheets); $i++) {
        echo "<link rel='stylesheet' href='$stylesheets[$i]'>";
    }

    echo "</head>";
}