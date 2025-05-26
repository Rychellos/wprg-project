<?php
$scripts = [];
require_once "functions/database.php";
require_once "functions/session.php";

if (Session::isLoggedIn()) {
    header('Location: ' . "index.php", true, 303);
    die();
}

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

// Invoke to start connection
Database::get_connection();

if (Database::checkRememberMe()) {
    if (Session::getLastPage()) {
        header('Location: ' . Session::getLastPage(), true, 303);

        Session::setLastPage(null);
        die();
    }

    header('Location: ' . "index.php", true, 303);
    die();
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

if ($userEmail != "" && $userPassword != "") {
    $user = Database::loginUser($userEmail, $userPassword);

    if ($user->id != null) {
        Session::fetchFromDatabase($user->id);

        if (isset($_POST["rememberMe"])) {
            Database::rememberUser($user->id);
        }

        header('Location: ' . "index.php", true, 303);
        die();
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
                require "./errors/databse_connection_error.php";
            } else {
                login(isset($userEmail) ? $userEmail : "", isset($user) ? $user->id ? 0 : 1 : 0, "Email lub hasło jest nieprawidłowe");

                $scripts["scripts/loginForm.js"] = 1;
            } ?>

        </main>
        <?php require "./templates/footer.html"; ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>