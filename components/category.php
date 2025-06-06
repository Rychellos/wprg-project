<?php

/**
 * Summary of categoryList
 * @param int $page
 * @param int $numPages
 * @return void
 */
function categoryList($page, $numPages, $categories, $quizId)
{
    $variables = [
        $page <= 1 ? ' disabled' : '',
    ];

    $range = 2; // How many pages to show on each side of current
    $start = max(1, $page - $range);
    $end = min($numPages, $page + $range);

    echo <<<EOD
        <div class="w-100 sticky-top">
                <h5 class="d-flex align-items-center">
                    Kategorie
                    <button id="addCategoryButton" class="btn ms-auto">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                </h5>

                <nav class="ms-3" aria-label="Quiz category page">
                    <ul class="pagination">
                        <li class="page-item {$variables[0]}">
                            <a class="page-link" href="category.php?page=
    EOD;

    echo max(1, $page - 1);

    echo <<<EOD
                " aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    EOD;

    if ($start > 1) {
        echo '<li class="page-item"><a class="page-link" href="category.php?page=1">1</a></li>';
        if ($start > 2) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        echo '<li class="page-item' . ($i == $page ? ' active' : '') . '">';
        echo '<a class="page-link" href="category.php?page=' . $i . '">' . $i . '</a>';
        echo '</li>';
    }

    if ($end < $numPages) {
        if ($end < $numPages - 1) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        echo '<li class="page-item"><a class="page-link" href="category.php?page=' . $numPages . '">' . $numPages . '</a></li>';
    }

    echo <<<EOD

    <!-- Next Button -->
    <li class="page-item 
    EOD;

    echo $page >= $numPages ? ' disabled' : '';

    echo <<<EOD
        ">
        <a class="page-link" href="category.php?page=
    EOD;

    echo min($numPages, $page + 1);

    echo <<<EOD
                               " aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <hr />

                <form action="void(0)" name="categorySelect" class="d-flex flex-column">
                    <select name="categoryId" id="categoryId" class="form-select d-md-none">
    EOD;

    for ($index = 0; $index < sizeof($categories); $index++) {
        $categoryIndex = $categories[$index]["id"];
        $categoryName = $categories[$index]["name"];
        $selected = (isset($quizId) ? $categoryIndex == $quizId : $index == 0) ? "selected" : "";

        echo <<<EOD
            <option value="$categoryIndex" $selected>
                $categoryName
            </option>
        EOD;
    }

    echo <<<EOD
        </select>
        <ul class="list-group list-group-flush rounded px-2 d-none d-md-block overflow-y-auto">
    EOD;

    for ($index = 0; $index < sizeof($categories); $index++) {
        $categoryIndex = $categories[$index]["id"];
        $categoryName = $categories[$index]["name"];
        $checked = (isset($quizId) ? $categoryIndex == $quizId : $index == 0) ? "checked" : "";

        echo <<<EOD
            <li class="list-group-item d-flex gap-2 bg-transparent w-100">
                <input type="radio" name="categoryId" class="btn-check" value="$categoryIndex" id="categoryId-$categoryIndex" $checked>
                <label class="btn w-100 text-start text-truncate" for="categoryId-$categoryIndex">$categoryName</label>
            </li>
        EOD;
    }

    echo <<<EOD
            </ul>
        </form>
    </div>
    EOD;
}

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

/**
 * Summary of category
 * @param int $page
 * @param int $numPages
 * @param object{description: string, id: float|int, name: string}[] $categories
 * @param object{description: string, id: float|int, name: string} $detail
 * @param object{name: string, quizId: float|int}[] $quizzesWithCategory
 * @return void
 */
function category($page, $numPages, $categories, $quizId, $detail, $quizzesWithCategory)
{
    echo <<<EOD
        <div class="container p-3 rounded bg-body-tertiary shadow">
            <div class="row">
                <div class="col-12 col-md-5 col-lg-4 p-2 d-flex gap-2">
    EOD;

    categoryList($page, $numPages, $categories, $quizId);

    echo <<<EOD
        <div class="d-none d-md-inline-block align-self-stretch">
                <div class="vr h-100"></div>
            </div>
        </div>

        <form class="col-12 col-md-7 col-lg-8 p-2" method="post">
            <input type="text" name="api" class="d-none" value="detail">
            <input type="text" name="categoryId" class="d-none" value="$detail->id ">
            <!-- Header -->
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

    <!-- Description -->
    <div class="p-2">
        <label for="categoryDescription" class="form-label">Opis</label>
        <textarea class="form-control" id="categoryDescription" name="categoryDescription"
            rows="3">$detail->description</textarea>
    </div>

    <hr />

    <!-- Quizzes List -->
    <div class="d-flex flex-column p-2">
        <h6 class="mb-2">Quizy z tą kategorią:</h6>
        <div id="quizzesWithCategory" class="d-flex flex-wrap gap-2 justify-content-between">
    EOD;

    for ($i = 0; $i < count($quizzesWithCategory); $i++) {
        echo "<a href=\"" . $quizzesWithCategory[$i]->quizId . "\" class=\"badge rounded-pill p-2 text-bg-info\">" . $quizzesWithCategory[$i]->name . "</a>";
    }

    echo <<<EOD
                    </div>
                </div>
            </form>
        </div>
    </div>
    EOD;

    echo <<<EOD
    EOD;
}
