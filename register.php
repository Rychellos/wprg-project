<?php
$scripts = [];
include_once "functions/database.php";

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    include_once $file;
}

// LOGIN HANDLING
$userName = "";
if (isset($_POST["userName"])) {
    preg_match("/^[\w]{6,255}$/", $_POST["userName"], $match);
    $userName = sizeof($match) > 0 ? $_POST["userName"] : "";
}

$userEmail = "";
if (isset($_POST["userEmail"])) {
    $userEmail = preg_match("/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/", $_POST["userEmail"], $match);
    $userEmail = sizeof($match) > 0 ? $_POST["userEmail"] : "";
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

$actionResult = -1;
if ($userName != "" && $userEmail != "" && $userPassword != "" && $userPassword == $userPasswordRepeat) {
    $actionResult = Database::createUser($userName, $userEmail, $userPassword);
}

if (isset($actionResult) && $actionResult == 0) {
    echo "DEBUG: Successfully created user account";
}

$actionMessage = "";

if ($actionResult > 0) {
    if ($actionResult == 1) {
        $actionMessage = "Użytkownik o takiej nazwie już istnieje!";
    } else {
        $actionMessage = "Ktoś już użył tego email'a!";
    }
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
                register($userName, $userEmail, $actionResult, $actionMessage);

                $scripts["scripts/registerForm.js"] = 1;
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