<?php
require_once "./functions/quiz.php";
require_once "./functions/category.php";

function addQuiz()
{
    $errorMessage = "";
    $description = "";
    $name = "";

    if (isset($_POST["add"], $_POST["quizAddName"])) {
        preg_match("/^.{6,255}$/", $_POST["quizAddName"], $match);
        $name = sizeof($match) > 0 ? $_POST["quizAddName"] : "";

        if (isset($_POST["quizAddDescription"])) {
            $description = $_POST["quizAddDescription"];
        }

        $id = 0;

        if ($name != "") {
            try {
                $id = Quiz::set(null, $name, $description, Session::getUserID());
            } catch (\Throwable $th) {
                $errorMessage = "Coś poszło nie tak podczas dodawania quizu.";
            }
        } else {
            $errorMessage = "Nieprawidłowa nazwa quizu.";
        }

        if (isset($_POST["quizAddCategory"], $id) && $id !== false) {
            Quiz::addCategory($id, $_POST["quizAddCategory"], Session::getUserID());
            unset($errorMessage);
        }
    }

    echo <<<EOD
        <form method="POST">
            <input class="d-none visually-hidden" name="add">
    EOD;

    baseInput(
        "quizAddName",
        "text",
        "Nazwa Quizu",
        null,
        ".{6,255}",
        $errorMessage,
        $name,
        $errorMessage === "" ? "" : "is-invalid",
        true,
        false
    );

    echo <<<EOD
        <div class="p-2">
            <label for="quizAddDescription" class="form-label">Opis</label>
            <textarea class="form-control" id="quizAddDescription" name="quizAddDescription" rows="3">$description</textarea>
        </div>

        <p class="form-text mb-0">Zaznacz kategorie:</p>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input id="addQuizCategorySearchBar" class="form-control" type="text" placeholder="Filtruj">
        </div>
            <div id="addQuizCategorySearchContainer" class="overflow-y-auto max-vh-md-25 row row-cols-2 px-1">
    EOD;

    $list = Category::fetchAll();

    for ($i = 0; $i < count($list); $i++) {
        echo <<<EOD
            <div class="form-check">
                <input class="form-check-input" id="addQuizCategorySelect-$i" type="checkbox" name="quizAddCategory[]" value="{$list[$i]->id}">
                <label for="addQuizCategorySelect-$i">{$list[$i]->name}</label>
            </div> 
        EOD;
    }

    $js = "";
    if (isset($_POST["add"], $errorMessage)) {
        $js = $errorMessage !== ""
            ? "addQuizAdminModal.show();"
            : "showToast(\"Dodano quiz.\", \"success\");";
    }

    echo <<<EOD
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                $js
            });
        </script>
    EOD;
}

function quizAdmin()
{
    $afterId = isset($_GET["afterIndex"]) ? (int) $_GET["afterIndex"] : null;
    [$items, $hasMore] = Quiz::fetchPage($afterId);
    $nextAfterId = count($items) > 0 ? end($items)->id : null;

    $id = isset($_GET["id"]) ? (int) $_GET["id"] : ($items[0]->id ?? -1);
    $detail = Quiz::get($id);

    $categories = Quiz::fetchCategories($detail->id);

    echo <<<EOD
        <div class="container p-3 rounded bg-body-tertiary shadow h-100">
            <div class="row h-100">
                <div class="col-12 col-md-5 col-lg-4 p-2 d-flex gap-2">
    EOD;

    idSelector($items, $hasMore, $afterId, $nextAfterId, "Quizy", "quizAdmin", $id);

    echo <<<EOD
                    <div class="d-none d-md-inline-block align-self-stretch">
                        <div class="vr h-100"></div>
                    </div>
                </div>
    EOD;

    if (!$detail) {
        echo ("<div class=\"col-12 col-md-7 col-lg-8 p-2 h-100\"></div>");
        return;
    }

    echo <<<EOD
                <form class="col-12 col-md-7 col-lg-8 p-2 h-100" method="post">
                    <input type="text" id="quizAdminId" name="id" class="d-none" value="$detail->id ">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div>
    EOD;

    baseInput(
        "quizAdminName",
        "text",
        "Nazwa Quizu",
        null,
        ".{2,255}",
        "Za długa nazwa",
        $detail->name,
        "",
        true,
        false
    );

    echo <<<EOD
            </div>
            <div class="d-flex flex-column gap-1">
                <button name="edit" class="btn btn-primary align-self-baseline ms-2">Zapisz</button>
                <button name="delete" class="btn btn-danger align-self-baseline ms-2">Usuń</button>
            </div>
        </div>

        <div>
            <a href="questionAdmin.php?quizId={$detail->id}">Zarządzaj listą pytań tego quizu.</a>
        </div>

        <hr />

        <div class="p-2">
            <label for="quizAdminDescription" class="form-label">Opis</label>
            <textarea class="form-control" id="quizAdminDescription" name="quizAdminDescription" rows="3">$detail->description</textarea>
        </div>

        <hr />

        <div class="d-flex flex-column p-2">
            <h6 class="mb-2">Kategorie tego quizu:</h6>
            <div id="quizCategories" class="d-flex flex-wrap gap-2 justify-content-between">
    EOD;

    for ($i = 0; $i < count($categories); $i++) {
        echo "<a href=\"category.php?id=" . $categories[$i]->id . "\" class=\"badge rounded-pill p-2 text-bg-info\">" . $categories[$i]->name . "</a>";
    }

    echo <<<EOD
                </form>
            </div>
        </div>
    EOD;
}