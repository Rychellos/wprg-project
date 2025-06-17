<?php
require_once "functions/quiz.php";
require_once "functions/category.php";

function quizString($num)
{
    $n = abs($num);

    // Sprawdź ostatnie dwie cyfry
    $lastTwo = $n % 100;
    $lastOne = $n % 10;

    if ($n == 1) {
        return "$num quiz";
    } elseif ($lastTwo >= 12 && $lastTwo <= 14) {
        return "$num quizów";
    } elseif ($lastOne >= 2 && $lastOne <= 4) {
        return "$num quizy";
    } else {
        return "$num quizów";
    }
}

function category()
{
    $afterId = isset($_GET["afterIndex"]) ? (int) $_GET["afterIndex"] : null;
    [$items, $hasMore] = Category::fetchPage($afterId);
    $nextAfterId = count($items) > 0 ? end($items)->id : null;

    $id = isset($_GET["id"]) ? (int) $_GET["id"] : ($items[0]->id ?? -1);
    $detail = Category::get($id);

    $quizzesWithCategory = Quiz::getWithCategory($id);

    echo <<<EOD
        <div class="container p-3 rounded bg-body-tertiary shadow">
            <div class="row">
                <div class="col-12 col-md-5 col-lg-4 p-2 d-flex gap-2">
    EOD;

    idSelector($items, $hasMore, $afterId, $nextAfterId, "Kategorie", "category", $id);

    echo <<<EOD
                    <div class="d-none d-md-inline-block align-self-stretch">
                        <div class="vr h-100"></div>
                    </div>
                </div>

                <form class="col-12 col-md-7 col-lg-8 p-2" method="post">
                    <input type="text" name="api" class="d-none" value="detail">
                    <input type="text" name="categoryId" id="categoryId" class="d-none" value="$detail->id">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div>
    EOD;

    baseInput(
        "categoryName",
        "text",
        "Nazwa kategorii",
        null,
        ".{2,255}",
        "Za długa nazwa",
        $detail->name,
        "",
        true,
        false
    );

    echo '<small class="text-muted" id="quizCount">';

    echo quizString(count($quizzesWithCategory));

    echo <<<EOD
            </small>
        </div>

        <div class="d-flex flex-column gap-1">
            <button name="edit" class="btn btn-primary align-self-baseline ms-2">Zapisz</button>
            <button name="delete" class="btn btn-danger align-self-baseline ms-2">Usuń</button>
        </div>
    </div>

    <hr />

    <div class="p-2">
        <label for="categoryDescription" class="form-label">Opis</label>
        <textarea class="form-control" id="categoryDescription" name="categoryDescription"
            rows="3">$detail->description</textarea>
    </div>

    <hr />

    <div class="d-flex flex-column p-2">
        <h6 class="mb-2">Quizy z tą kategorią:</h6>
        <div id="quizzesWithCategory" class="d-flex flex-wrap gap-2 justify-content-between">
    EOD;

    for ($i = 0; $i < count($quizzesWithCategory); $i++) {
        echo "<a href=\"quizAdmin.php?id={$quizzesWithCategory[$i]->quizId}\" class=\"badge rounded-pill p-2 text-bg-info\">{$quizzesWithCategory[$i]->name}</a>";
    }

    echo <<<EOD
                    </div>
                </div>
            </form>
        </div>
    </div>
    EOD;
}
