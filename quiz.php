<?php
$scripts = array();
require_once "functions/session.php";
require_once "functions/attempt.php";

Session::setLastPage("quizViewList.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn()) {
    header('Location: login.php', true, 303);
    die();
}

if(!isset($_GET["id"]) || (int) $_GET["id"] <= 0){
    header('Location: quizViewList.php', true, 303);
    Session::setLastPage("quizViewList.php");
    die();
}

$attemptDetails = Attempt::get(Session::getUserID());

if($attemptDetails->quizId != $_GET["id"]){
    Attempt::end($attemptDetails->id);
    Attempt::start(Session::getUserID(), $_GET["id"]);
}

$attemptDetails = Attempt::get(Session::getUserID());

$currentQuestion = Attempt::getNextQuestion($attemptDetails->id);
$userAnswerId = Attempt::getAnswerId($attemptDetails->id, $currentQuestion->id);

if(isset($_POST["commitAnswer"])){
    $answers = Question::fetchAnswers($currentQuestion->id);

    if(isset($_POST["answerText"])) {
        $items = $_POST["answerText"];

        while ($answer = array_shift($items)) {
            Attempt::addAnswerContent($userAnswerId, $answer, null);
        }
    } else if (isset($_POST["answerNumeric"])) {
        $items = $_POST["answerNumeric"];

        while ($answerIndex = (int) array_shift($items)) {
            Attempt::addAnswerContent($userAnswerId, $answers[$answerIndex]->textContent, $answerIndex);
        }
    }

    Header("Location: quiz.php?id=" . $_GET["id"], true, 303);
    die();
}
?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Lista quizów"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
<?php navbar(); ?>

<div class="d-flex flex-column h-100 overflow-y-auto">
    <main class="container-fluid mb-3">

        <?php
        if (Database::has_errored()) {
            require "errors/databse_connection_error.php";
        } else {
            if($currentQuestion) {
                question();
            } else {
                quizSummary();
            }
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

</html>