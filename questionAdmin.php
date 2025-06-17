<?php
$scripts = array();
require_once "functions/database.php";
require_once "functions/user.php";
require_once "functions/session.php";
require_once "functions/questionImages.php";

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

// security

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn()) {
    header('Location: login.php', true, 303);
    Session::setLastPage("questionAdmin.php");
    die();
}

if (!Session::isModerator() && !Session::isAdmin()) {
    header('Location: login.php', true, 303);
    Session::setLastPage("index.php");
    die();
}

// check ids

if (!(isset($_GET["quizId"]) || isset($_POST["quizId"]))) {
    Session::setLastPage("quizAdmin.php");
    header("Location: quizAdmin.php", true, 303);
    die();
}

$id = null;

if (isset($_GET["id"]) && (int) $_GET["id"] > 0) {
    $id = (int) $_GET["id"];
}

if (isset($_POST["id"]) && (int) $_POST["id"] > 0) {
    $id = (int) $_POST["id"];
}

$quizId = null;

if (isset($_GET["quizId"]) && (int) $_GET["quizId"] > 0) {
    $quizId = (int) $_GET["quizId"];
}

if (isset($_POST["quizId"]) && (int) $_POST["quizId"] > 0) {
    $quizId = (int) $_POST["quizId"];
}

// manipulations

if (isset($_POST["delete"], $_GET["id"])) {
    Question::delete($id, Session::getUserID());
    //TODO: Powiadomienie
}

if (isset($_POST["questionSet"], $_POST["questionSetId"], $_POST["questionSetType"])) {
    Question::set(
        $_POST["questionSetId"] == "null" ? null : $_POST["questionSetId"],
        $quizId,
        $_POST["questionSetType"],
        Session::getUserID()
    );
}

if (isset($_GET["api"]) && $_GET["api"] == "detail") {
    $detailData = Question::get($id);

    if (!$detailData) {
        die(json_encode("false"));
    }

    $detailData = [
        "id" => $detailData->id,
        "quizId" => $detailData->quizId,
        "type" => $detailData->type,
        // "questionContents" => Question::fetchContents($detailData->id)
    ];

    die(json_encode($detailData));
}

$contentAddError = [];

/** 
 * @return string
 */
function questionAddContentsImage()
{
    global $contentAddError;
    $upload = $_FILES["questionContentSetImage"];

    if ($upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) {
        $contentAddError[] = "Nie udało się przesłać obrazu.";
        return "";
    }

    $imageInfo = getimagesize($upload['tmp_name']);
    if ($imageInfo === false) {
        $contentAddError[] = "Nieprawidłowy format obrazu.";
        return "";
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imageInfo['mime'], $allowedTypes)) {
        $contentAddError[] = "Nieobsługiwany format obrazu.";
        return "";
    }

    $hash = hash('sha256', $_GET["quizId"] . microtime(true));
    $filename = $hash . '.png';

    $success = QuestionImagesContent::writeHashed($filename, $upload['tmp_name']);

    if (!$success) {
        $contentAddError[] = "Coś poszło nie tak podczas zapisu obrazu.";
        return "";
    }

    return $filename;
}

/** 
 * @return string
 */
function questionAddAnwserImage()
{
    global $contentAddError;
    $upload = $_FILES["questionAnwserSetImage"];

    if ($upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) {
        $contentAddError[] = "Nie udało się przesłać obrazu.";
        return "";
    }

    $imageInfo = getimagesize($upload['tmp_name']);
    if ($imageInfo === false) {
        $contentAddError[] = "Nieprawidłowy format obrazu.";
        return "";
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imageInfo['mime'], $allowedTypes)) {
        $contentAddError[] = "Nieobsługiwany format obrazu.";
        return "";
    }

    $hash = hash('sha256', $_GET["quizId"] . microtime(true));
    $filename = $hash . '.png';

    $success = QuestionImagesAnswer::writeHashed($filename, $upload['tmp_name']);

    if (!$success) {
        $contentAddError[] = "Coś poszło nie tak podczas zapisu obrazu.";
        return "";
    }

    return $filename;
}

if (
    isset(
    $_POST["questionContentSet"],
    $_POST["questionContentSetContentId"],
    $_POST["questionContentSetQuestionId"],

    $_FILES["questionContentSetImage"],
    $_FILES["questionContentSetVideo"],
    $_POST["questionContentSetText"],
)
) {
    $image = "";

    if (isset($_FILES["questionContentSetImage"])) {
        $image = questionAddContentsImage();
    }

    $text = "";

    if (isset($_POST["questionContentSetText"])) {
        $text = $_POST["questionContentSetText"];
    }

    //TODO: VIDEO

    Question::setContents(
        $_POST["questionContentSetContentId"] == "null" ? null : $_POST["questionContentSetContentId"],
        $_POST["questionContentSetQuestionId"],
        $text,
        $image,
        "",
        Session::getUserID()
    );
}

if (
    isset(
    $_POST["questionAnwserSet"],
    $_POST["questionAnwserSetAnwserId"],
    $_POST["questionAnwserSetQuestionId"],

    $_FILES["questionAnwserSetImage"],
    $_FILES["questionAnwserSetVideo"],
    $_POST["questionAnwserSetText"],
)
) {
    $image = "";

    if (isset($_FILES["questionAnwserSetImage"])) {
        $image = questionAddAnwserImage();
    }

    $text = "";

    if (isset($_POST["questionAnwserSetText"])) {
        $text = $_POST["questionAnwserSetText"];
    }

    //TODO: VIDEO

    Question::setAnwser(
        $_POST["questionAnwserSetAnwserId"] == "null" ? null : $_POST["questionAnwserSetAnwserId"],
        $_POST["questionAnwserSetQuestionId"],
        $text,
        $image,
        "",
        isset($_POST["questionAnwserSetIsValid"]),
        Session::getUserID()
    );
}
?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Zarządzanie pytaniami", ["styles/activeButton.css"]); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column bg-body h-100 overflow-y-auto align-items-stretch">
        <main class="container-fluid bg-body py-3 d-flex">
            <?php if (Database::has_errored()) {
                require "./errors/databse_connection_error.php";
            } else {
                questionAdmin();
            } ?>
        </main>

        <?php require "./templates/footer.html"; ?>
    </div>

    <?php

    showModal(
        "Dodawanie pytania",
        "addQuestion",
        "<button class=\"btn btn-success\">Dodaj</button>",
        "",
        "POST",
        "questionAdminModal"
    );

    $scripts["scripts/questionAdmin.js"] = 1;
    $scripts["scripts/toasts.js"] = 1;

    echo "<script defer>";

    for ($i = 0; $i < count($contentAddError); $i++) {
        echo "showToast(\"$contentAddError[$i]\", \"danger\")";
    }

    echo "</script>";

    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>