<?php
$scripts = [];
include_once "functions/database.php";

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    include_once $file;
}

// LOGIN HANDLING
$userEmail = "";
if (isset($_POST["userEmail"])) {
    $userEmail = $_POST["userEmail"];
}

$userPassword = "";
if (isset($_POST["userPassword"])) {
    $userPassword = $_POST["userPassword"];
}

$userPasswordRepeat = "";
if (isset($_POST["userPasswordRepeat"])) {
    $userPasswordRepeat = $_POST["userPasswordRepeat"];
}

$connection = Database::get_connection();

if ($userEmail != "" && $userPassword != "" && $userPassword == $userPasswordRepeat) {

    //get database info

    echo "debug: account created";
}

?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Logowanie"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">

    <?php navbar(); ?>

    <div class="d-flex flex-column h-100 overflow-y-auto bg-body-tertiary">
        <main class="container-fluid bg-body pb-3 h-100">
            <?php if (Database::has_errored()) {
                include "./errors/databse_connection_error.php";
            } else {
                include "./templates/register.html";

                $scripts["/scripts/registerForm.js"] = 1;
            } ?>
        </main>
        <?php include "./templates/footer.html"; ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>