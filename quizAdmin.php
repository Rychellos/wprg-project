<?php
$scripts = array();
require_once "functions/database.php";
require_once "functions/user.php";
require_once "functions/session.php";

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn()) {
    Session::setLastPage("quizAdmin.php");
    header('Location: login.php', true, 303);
    die();
}

if (!Session::isModerator() && !Session::isAdmin()) {
    Session::setLastPage("index.php");
    header('Location: index.php', true, 303);
    die();
}

$id = 0;

if (isset($_GET["id"]) && is_numeric($_GET["id"]) && (int) $_GET["id"] > 0) {
    $id = (int) $_GET["id"];
}

if (isset($_POST["delete"], $_GET["id"])) {
    Quiz::delete($id, Session::getUserID());
    //TODO: Powiadomienie
}

if (isset($_POST["add"])) {
    $id = Quiz::getCount();
}

if (isset($_GET["api"]) && $_GET["api"] == "detail") {
    $detailData = Quiz::get($id);

    if (!$detailData) {
        die(json_encode("false"));
    }

    $detailData = [
        "id" => $detailData->id,
        "name" => $detailData->name,
        "description" => $detailData->description,
        "quizCategories" => Quiz::fetchCategories($id)
    ];

    die(json_encode($detailData));
}
?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Zarządzanie quizami", ["styles/activeButton.css"]); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column bg-body h-100 overflow-y-auto align-items-stretch">
        <main class="container-fluid bg-body py-3 h-100">
            <?php if (Database::has_errored()) {
                require "./errors/databse_connection_error.php";
            } else {
                quizAdmin();
            } ?>
        </main>
        <?php require "./templates/footer.html"; ?>
    </div>

    <?php

    $scripts["scripts/quizAdmin.js"] = 1;
    $scripts["scripts/idSelector.js"] = 1;
    $scripts["scripts/toasts.js"] = 1;

    foreach ($scripts as $key => $value) {
        echo "<script src='$key' defer></script>";
    }

    showModal(
        "Dodawanie quizu",
        "addQuiz",
        "<button class=\"btn btn-success\">Dodaj</button>",
        "",
        "POST",
        "quizAddModal"
    );
    ?>
</body>