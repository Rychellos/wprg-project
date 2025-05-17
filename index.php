<?php
$scripts = array();
include_once("functions/database.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    include_once $file;
}

?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Quiz Serwis"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column h-100 overflow-y-auto">
        <main class="container-fluid mb-3">

            <?php Database::get_connection();

            if (Database::has_errored()) {
                include "errors/databse_connection_error.php";
            } else {
                echo "<h1 class='primary-secondary-gradient animated'>Animowany kolor tekstu działa</h1>";
            } ?>

        </main>
        <?php include("./templates/footer.html"); ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>

</html>