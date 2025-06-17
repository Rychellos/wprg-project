<?php
$scripts = [];
require_once "functions/user.php";
require_once "functions/session.php";

if (Session::isLoggedIn()) {
    header('Location: ' . "index.php", true, 303);
    die();
}

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
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
    $actionResult = User::create($userName, $userEmail, $userPassword);
}

if (isset($actionResult) && $actionResult == 0) {
    $user = User::login($userEmail, $userPassword);

    Session::setIsLoggedIn(true);
    Session::setIsModerator($user->type == "moderator");
    Session::setIsAdmin($user->type == "administrator");
    Session::setUserID($user->id);
    Session::setUserEmail($user->email);
    Session::setUserName($user->name);

    header('Location: ' . "index.php", true, 303);
    die();
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
                require "./errors/databse_connection_error.php";
            } else {
                register($userName, $userEmail, $actionResult, $actionMessage);

                $scripts["scripts/registerForm.js"] = 1;
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