<?php
require_once "./functions/question.php";
require_once "./functions/quiz.php";

function addQuestion()
{
    echo <<<EOD
        <input class="d-none visually-hidden" name="questionSet">
        <input class="d-none visually-hidden" name="questionSetId" value="null">
        <label for="questionSetType">Wybierz typ nowego pytania</label>
        
        <select class="form-select" name="questionSetType" id="questionSetType">
    EOD;

    for ($i = 0; $i < count(QUESTION_TYPES); $i++) {
        $enabled = QUESTION_TYPES_TEXT_ENABLED[$i] ? "" : "disabled";

        echo "<option value=\"" . QUESTION_TYPES[$i] . "\" $enabled>";
        echo QUESTION_TYPES_TEXT[$i];
        echo "</option>";
    }

    echo "</select>";
}

function questionAdmin()
{
    global $quizId;
    $afterId = isset($_GET["afterIndex"]) ? (int) $_GET["afterIndex"] : null;
    [$items, $hasMore] = Question::fetchPage($quizId, $afterId);
    $nextAfterId = count($items) > 0 ? end($items)->id : null;

    for ($i = 0; $i < count($items); $i++) {
        $items[$i] = (object) [
            "id" => $items[$i]->id,
            "name" => "Pytanie " . ($i + 1)
        ];
    }

    echo <<<EOD
        <div class="container p-3 rounded bg-body-tertiary shadow">
            <div class="row h-100">
                <div class="col-12 col-md-5 col-lg-4 p-2 d-flex gap-2">
                    <div class="d-flex flex-column w-100">
                        <a href="quizAdmin.php">Powrót</a>
    EOD;

    $id = isset($_GET["id"]) ? (int) $_GET["id"] : (isset($items[0]) ? $items[0]->id : -1);

    idSelector($items, $hasMore, $afterId, $nextAfterId, "Pytania", "questionAdmin", $id);

    echo <<<EOD
                    </div>
                    <div class="d-none d-md-inline-block align-self-stretch">
                        <div class="vr h-100"></div>
                    </div>
                </div>
    EOD;

    $questionDetail = Question::get($id);

    if (!$questionDetail) {
        echo ("<div class=\"col-12 col-md-7 col-lg-8 p-2 h-100\"></div>");
        return;
    }

    $questionContents = Question::fetchContents($questionDetail->id);
    $questionAnswers = Question::fetchAnswers($questionDetail->id);

    echo <<<EOD
        <div class="col-12 col-md-7 col-lg-8 p-2 h-100">
            <form method="post" class="d-flex justify-content-between align-items-center p-2">
                <div>
                    <input class="d-none visually-hidden" name="questionSet">
                    <input type="text" id="questionSetId" name="questionSetId" class="d-none visually-hidden" value="$questionDetail->id ">
                    <label for="questionSetType">Wybierz typ pytania</label>
                    <select class="form-select" name="questionSetType" id="questionSetType">
    EOD;

    for ($i = 0; $i < count(QUESTION_TYPES); $i++) {
        $selected = QUESTION_TYPES[$i] === $questionDetail->type ? "selected" : "";
        $enabled = QUESTION_TYPES_TEXT_ENABLED[$i] ? "" : "disabled";

        echo "<option value=\"" . QUESTION_TYPES[$i] . "\" $selected $enabled>";
        echo QUESTION_TYPES_TEXT[$i];
        echo "</option>";
    }

    echo <<<EOD
                    </select>
                </div>
                <div class="d-flex flex-column gap-1">
                    <button class="btn btn-primary align-self-baseline ms-2">Zapisz</button>
                    <button name="delete" class="btn btn-danger align-self-baseline ms-2">Usuń</button>
                </div>
            </form>

            <hr />

            <div class="d-flex flex-column p-2">
                <h6 class="mb-2">Zawartość pytania:</h6>
                <div id="questionContents" class="d-flex flex-wrap gap-2 justify-content-between">
    EOD;

    for ($i = 0; $i < count($questionContents); $i++) {
        $video = "brak filmu";
        $image = "brak obrazu";

        if ($questionContents[$i]->imageSrc !== "") {
            $src = QuestionImagesContent::read($questionContents[$i]->imageSrc);
            $image = "<img class=\"img-fluid rounded rounded-5\" src=\"$src\" />";
        }

        if ($questionContents[$i]->videoSrc !== "") {
            $video = "<video class=\"object-fit-contain rounded\" src=\"{$questionContents[$i]->videoSrc}\" />";
        }

        echo <<<EOD
            <div class="mb-3 bg-body p-2 rounded border">
                <p>{$questionContents[$i]->textContent}</p>
                <div class="max-vw-25 d-flex gap-2">
                    <div class="max-vh-25 d-flex object-fit-contain">
                        $image
                    </div>
                    <div class="max-vh-25 d-flex object-fit-contain">
                        $video
                    </div>
                    
                </div>
            </div>
        EOD;
    }

    echo <<<EOD
                </div>
                <form class="p-2" method="post" enctype="multipart/form-data">
                    <input type="text" name="questionContentSetQuestionId" class="d-none" value="{$questionDetail->id}">
                    <input type="text" name="questionContentSetContentId" class="d-none" value="null">
                    
                    <div class="pb-3">
                        <label for="questionContentSetImage" class="form-label">Dodaj zdjęcie do zawartośći</label>
                        <input class="form-control" type="file" id="questionContentSetImage" name="questionContentSetImage" />
                    </div>

                    <div class="pb-3 d-none visiblity-hidden">
                        <label for="questionContentSetVideo" class="form-label">Dodaj wideo do zawartośći</label>
                        <input class="form-control" type="file" id="questionContentSetVideo" name="questionContentSetVideo" />
                    </div

                    <div class="pb-3">
                        <label for="questionContentSetText" class="form-label">Dodaj zawartość tekstową</label>
                        <textarea class="form-control" id="questionContentSetText" name="questionContentSetText" rows="3"></textarea>
                    </div>

                    <div>
                        <button name="questionContentSet" class="btn btn-secondary">Dodaj zawartość</button>
                    </div>
                </form>

                    <hr />

                    <h6 class="mb-2">Odpowiedzi pytania:</h6>
                    <div id="questionAnswers" class="d-flex flex-wrap gap-2 justify-content-between">
    EOD;
    for ($i = 0; $i < count($questionAnswers); $i++) {
        $video = "brak filmu";
        $image = "brak obrazu";

        if ($questionAnswers[$i]->imageSrc !== "") {
            $src = QuestionImagesAnswer::read($questionAnswers[$i]->imageSrc);
            $image = "<img class=\"img-fluid rounded rounded-5\" src=\"$src\" />";
        }

        if ($questionAnswers[$i]->videoSrc !== "") {
            $video = "<video class=\"object-fit-contain rounded\" src=\"{$questionAnswers[$i]->videoSrc}\" />";
        }

        $borderColor = $questionAnswers[$i]->isValid ? "border-success" : "border-danger";

        echo <<<EOD
            <div class="mb-3 bg-body p-2 rounded border $borderColor">
                <p>{$questionAnswers[$i]->textContent}</p>
                <div class="max-vw-25 d-flex gap-2">
                    <div class="max-vh-25 d-flex object-fit-contain">
                        $image
                    </div>
                    <div class="max-vh-25 d-flex object-fit-contain">
                        $video
                    </div>
                    
                </div>
            </div>
        EOD;
    }
    echo <<<EOD
                    </div>
                    <form class="border border-1 rounded p-2 bg-body" method="post" enctype="multipart/form-data">
                        <input type="text" name="questionAnwserSetQuestionId" class="d-none" value="{$questionDetail->id}">
                        <input type="text" name="questionAnwserSetAnwserId" class="d-none" value="null">

                        <div class="pb-3 d-none visibility-hidden">
                            <label for="questionAnwserSetImage" class="form-label">Dodaj zdjęcie do odpowiedzi</label>
                            <input class="form-control" type="file" id="questionAnwserSetImage" name="questionAnwserSetImage" />
                        </div>

                        <div class="pb-3 d-none visibility-hidden">
                            <label for="questionAnwserSetVideo" class="form-label">Dodaj wideo do odpowiedzi</label>
                            <input class="form-control" type="file" id="questionAnwserSetVideo" name="questionAnwserSetVideo" />
                        </div>

                        <div class="pb-3">
                            <label for="questionAnwserSetText" class="form-label">Dodaj odpowiedzi tekstową</label>
                            <textarea class="form-control" id="questionAnwserSetText" name="questionAnwserSetText" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-content-center">
                            <button name="questionAnwserSet" class="btn btn-secondary">Dodaj odpowiedź</button>
                            <div>
                                <div class="form-check form-switch">
                                    <input value="" class="form-check-input" name="questionAnwserSetIsValid" type="checkbox" role="switch" id="questionAnwserSetIsValid">
                                    <label class="form-check-label" for="questionAnwserSetIsValid">Czy prawidłowa?</label>
                                </div>
                            </div>
                        </div>
                    </form>
                <div>
            </div>
        </div>
    EOD;
}