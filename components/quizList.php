<?php
function quizList()
{
    $afterId = isset($_GET["afterIndex"]) ? (int) $_GET["afterIndex"] : null;
    [$items, $hasMore] = Quiz::fetchPage($afterId);
    $nextAfterId = count($items) > 0 ? end($items)->id : null;

    echo <<<EOD
    <div class="container py-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="quiz-grid">
    EOD;

    foreach ($items as $quiz) {
        echo <<<EOD
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">{$quiz->name}</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{$quiz->description}</p>
                        <a href="quiz.php?id={$quiz->id}" class="btn btn-primary">Rozpocznij quiz</a>
                    </div>
                </div>
            </div>
        EOD;
    }

    echo '</div>';

    if ($hasMore) {
        echo <<<EOD
        <div class="text-center mt-4">
            <button class="btn btn-outline-primary" id="load-more" data-next-id="{$nextAfterId}">
                Załaduj więcej
            </button>
        </div>
        EOD;
    }

    echo '</div>';

    global $scripts;
    $scripts["scripts/quiz-list.js"] = 1;
}