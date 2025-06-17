<?php
$scripts = array();
require_once "functions/user.php";
require_once "functions/quiz.php";
require_once "functions/session.php";

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
            quizList();
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