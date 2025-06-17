<?php
$scripts = array();
require_once "functions/user.php";
require_once "functions/session.php";

Session::setLastPage("aboutMe.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn()) {
    header('Location: ' . "login.php", true, 303);
    die();
}

?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Ukończone quizy"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
<?php navbar(); ?>

<div class="d-flex flex-column h-100 overflow-y-auto">
    <main class="container-fluid mb-3">

        <?php Database::get_connection();

        if (Database::has_errored()) {
            require "errors/databse_connection_error.php";
        } else {
            $list = Database::get_connection()->query("SELECT * FROM Quiz JOIN QuizAttempt ON Quiz.id = QuizAttempt.quizId WHERE QuizAttempt.userId = " . Session::getUserId())->fetchAll(PDO::FETCH_OBJ);

            echo <<<EOD
                <div class="container p-3">
                    <div class="rounded p-2 bg-body-tertiary shadow table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nazwa Quizu</th>
                                    <th>Data ukończenia</th>
                                    <th>Akcja</th>
                                </tr>
                            </thead>
                        <tbody>
            EOD;

            foreach ($list as $quiz) {
                echo "<tr>
                    <td>{$quiz->started}</td>
                    <td>{$quiz->name}</td>
                    <td><a class='btn btn-primary' href='quiz.php?id={$quiz->quizId}'>Zagraj ponownie</a></td>
                </tr>";
            }

            echo <<<EOD
                            </tbody>
                        </table>
                    </div
                </div>
            EOD;

        }

        ?>

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