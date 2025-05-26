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

    card("Test 1", "Lorem Ipsum Solor Dir Amet", "quiz/test_1", "https://placehold.co/600x400");
    card("Test 2", "Lorem Ipsum Solor Dir Amet", "quiz/test_2", "https://placehold.co/734x600");
    card("Test 3", "Lorem Ipsum Solor Dir Amet", "quiz/test_3", "https://placehold.co/1920x1080");
    card("Test 4", "Lorem Ipsum Solor Dir Amet", "quiz/test_4", "https://placehold.co/1080x1920");
    card("Test 5", "Lorem Ipsum Solor Dir Amet", "quiz/test_5", null);

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

    echo <<<EOD
        <div class="container mt-5 d-flex flex-column gap-4">
            <div class="text-center">
                <h1 class="d-inline primary-secondary-gradient animated">Witaj$user na Quiz Serwisie!</h1>
            </div>

            <div class="p-3 rounded-2 bg-body-tertiary shadow">
                <div class="row justify-content-between">
                    <div class="col-12 col-lg-6 d-flex gap-3 flex-column">
                        <h3 class="mb-0">Quiz dnia:</h3>
                        <p class="fs-3 mb-0">{PODAJ QUIZ DNIA}</p>
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