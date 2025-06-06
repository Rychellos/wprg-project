<?php
$scripts = array();
require_once "functions/database.php";
require_once "functions/session.php";

Session::setLastPage("aboutMe.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
Database::checkRememberMe();

if (!Session::isLoggedIn()) {
    header('Location: ' . "login.php", true, 303);
    die();
}

?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("O mnie"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column h-100 overflow-y-auto">
        <main class="container-fluid mb-3">

            <?php Database::get_connection();

            if (Database::has_errored()) {
                require "errors/databse_connection_error.php";
            } else {
                aboutMe();
            }

            $scripts["scripts/toasts.js"] = 1;
            $scripts["scripts/aboutMe.js"] = 1;

            ?>

        </main>
        <?php require "./templates/footer.html"; ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>

</html>