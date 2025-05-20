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
<?php head("Nie znaleziono"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column h-100 overflow-y-auto">
        <main class="container-fluid mb-3">
            <div class="container my-5 h-100">
                <h2>UPS, zgubiłeś się co? Tutaj masz powrót na <a href="">Stronę Główną</a>.</h2>
            </div>
        </main>
        <?php include "./templates/footer.html"; ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>

</html>