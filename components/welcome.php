<?php
require_once "functions/session.php";

function card($titile, $description, $link, $imageSource)
{
    $image = "";

    if (isset($imageSource)) {
        $image = "<img src='$imageSource' class='card-img-cap object-fit-cover' style='max-height: 9rem' alt='Nie udało się wczytać podglądu quizu'>";
    }

    echo <<<EOD
        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
            <div class="card shadow" style="max-height: 25rem">
                <div class="card-header">
                    <h5 class="card-title">$titile</h5>
                </div>
                $image
                <div class="card-body">
                    <p class="card-text">$description</p>
                    <a href="$link" class="btn btn-primary">Zobacz</a>
                </div>
            </div>
        </div>
    EOD;
}
function popular()
{
    $conn = Database::get_connection();

    echo <<<EOD
        <div class="container mt-4">
            <h1 class="text-center">Najpopularniejsze quizy:</h1>

            <div class="bg-body-tertiary rounded-2 p-3 pb-0 shadow">
                <div class="row justify-content-center">
    EOD;

    $mostPopular = $conn->query("SELECT qa.quizId, Quiz.name, Quiz.description, COUNT(qa.id) AS attempts FROM QuizAttempt qa JOIN Quiz ON qa.quizId = Quiz.id GROUP BY qa.quizId ORDER BY attempts DESC LIMIT 5;")->fetchAll(PDO::FETCH_OBJ);

    for ($i = 0; $i < count($mostPopular); $i++) {
        card($mostPopular[$i]->name, $mostPopular[$i]->description, "quiz.php?id=" . $mostPopular[$i]->quizId, null);;
    }

    echo <<<EOD
                </div>
            </div>
        </div>
    EOD;
}

function banner()
{
    $user = "";

    if (Session::getUserName() != null) {
        $user = ' ' . Session::getUserName();
    }

    $dailyQuiz = Database::get_connection()->query("SELECT q.* FROM Quiz q JOIN DailyQuiz dq ON dq.quizId = q.id WHERE dq.selectedDate = CURDATE();")->fetch(PDO::FETCH_OBJ);

    if(!$dailyQuiz) {
        Database::get_connection()->query("INSERT INTO DailyQuiz (quizId, selectedDate) VALUES ((SELECT id FROM Quiz ORDER BY RAND() LIMIT 1), CURDATE());");
        $dailyQuiz = Database::get_connection()->query("SELECT q.* FROM Quiz q JOIN DailyQuiz dq ON dq.quizId = q.id WHERE dq.selectedDate = CURDATE();")->fetch(PDO::FETCH_OBJ);
    }

    echo <<<EOD
        <div class="container mt-5 d-flex flex-column gap-4">
            <div class="text-center">
                <h1 class="d-inline primary-secondary-gradient animated">Witaj$user na Quiz Serwisie!</h1>
            </div>

            <div class="p-3 rounded-2 bg-body-tertiary shadow">
                <div class="row justify-content-between">
                    <div class="col-12 col-lg-6 d-flex gap-3 flex-column">
                        <h3 class="mb-0">Quiz dnia:</h3>
                        <p class="fs-3 mb-0">
                            <a href="quiz.php?id={$dailyQuiz->id}">{$dailyQuiz->name}</a>
                        </p>
                    </div>
                    <div class="col-12 col-lg-6 mt-3 mt-lg-0">
                        <div class="d-flex gap-3 align-items-center pe-2">
                            <h5 class="mb-0">Do nowego codzienniego quizu:</h3>
                            <p class="fs-5 mb-0 next-quiz-timer">{PODAJ CZAS}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    EOD;

    global $scripts;
    $scripts["scripts/nextQuizTimer.js"] = 1;
}

function welcome()
{
    banner();
    popular();
}