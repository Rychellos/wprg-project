<?php

require_once "functions/session.php";
require_once "functions/question.php";
require_once "functions/attempt.php";
require_once "functions/questionImages.php";
function question()
{
    $questionDetails = Attempt::getNextQuestion(Attempt::get(Session::getUserID())->id);
    $questionContents = Question::fetchContents($questionDetails->id);
    $questionAnswers = Question::fetchAnswers($questionDetails->id);

    echo <<<EOD
        <div class="container p-3">
            <div class="rounded p-2 bg-body-tertiary shadow">
    EOD;

    foreach ($questionContents as $content) {
        $img = "";

        if($content->imageSrc !== "") {
            $img = "<img class=\"img-fluid rounded\" src=\"" . (QuestionImagesContent::read($content->imageSrc)) . "\" />";
        }

        echo <<<EOD
            <p>{$content->textContent}</p>
            <div class="d-flex gap-2">
                <div class="max-vh-25 d-flex object-fit-contain">
                    $img
                </div>
            </div>

            <hr />
        EOD;
    }

    echo <<<EOD
        <form method="post">
            <input type="hidden" name="commitAnswer" class="d-none visually-hidden" />
            <p>Twoja odpowiedź:</p>
    EOD;

    switch ($questionDetails->type) {
        case QUESTION_TYPES[1]:
            echo <<<EOD
                    <textarea class="form-control" name="answerText[]" id="answer" rows="3"></textarea>
                    <hr />
            EOD;
        break;
        case QUESTION_TYPES[2]:
            for ($i = 0; $i < count($questionAnswers); $i++) {
                $answer = $questionAnswers[$i];

                echo <<<EOD
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answerNumeric[]" id="answerRadio-{$i}" value="{$i}">
                        <label class="form-check-label" for="answerRadio-{$i}">{$answer->textContent}</label>
                    </div>
                EOD;
            }

            echo "<hr />";
        break;
        case QUESTION_TYPES[3]:
            for ($i = 0; $i < count($questionAnswers); $i++) {
                $answer = $questionAnswers[$i];

                echo <<<EOD
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="answerNumeric[]" id="answerCheckbox-{$i}" value="{$i}">
                        <label class="form-check-label" for="answerCheckbox-{$i}">{$answer->textContent}</label>
                    </div>
                EOD;
            }

            echo "<hr />";
        break;
    }

    echo <<<EOD
                    <button class="btn btn-primary">Zatwierdź</button>
                </form>
            </div>
        </div>
    EOD;
}

function quizSummary() {
    $attemptDetails = Attempt::get(Session::getUserID());
    $stmt = Database::get_connection()->prepare("SELECT * FROM `Question` WHERE `quizId`=:quizId");
    $stmt->execute(["quizId" => $attemptDetails->quizId]);
    $questions = $stmt->fetchAll(PDO::FETCH_OBJ);

    echo <<<EOD
        <div class="container p-3">
            <div class="rounded p-2 bg-body-tertiary shadow">
                <h1>Podsumowanie</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pytanie</th>
                            <th>Poprawne odpowiedzi</th>
                            <th>Twoje odpowiedzi</th>
                        </tr>
                    </thead>
                    <tbody>
    EOD;
    foreach ($questions as $question) {
        $contents = Question::fetchContents($question->id);
        $validAnswers = Question::fetchAnswers($question->id);

        $stmt = Database::get_connection()->prepare("
            SELECT UAC.* 
            FROM UserAnswerContent UAC
            JOIN UserAnswer UA ON UA.id = UAC.userAnswerId
            WHERE UA.attemptId = :attemptId AND UA.questionId = :questionId
        ");
        $stmt->execute([
            "attemptId" => $attemptDetails->id,
            "questionId" => $question->id
        ]);
        $userAnswers = $stmt->fetchAll(PDO::FETCH_OBJ);

        $questionText = implode(" ", array_map(fn($c) => $c->textContent, $contents));
        $validAnswersText = implode(", ", array_map(fn($a) => $a->textContent, array_filter($validAnswers, fn($a) => $a->isValid)));
        $userAnswersText = implode(", ", array_map(fn($a) => $a->textContent, $userAnswers));

        echo <<<EOD
            <tr>
                <td>{$questionText}</td>
                <td>{$validAnswersText}</td>
                <td>{$userAnswersText}</td>
            </tr>
        EOD;
    }
    echo <<<EOD
                    </tbody>
                </table>
            </div>
            
            <div class="p-2 d-flex gap-4">
                <a href="quiz.php?id={$attemptDetails->quizId}" class="btn btn-secondary">Jeszcze raz!!</a>
                <a href="quizViewList.php" class="btn btn-secondary">Powrót do listy</a>
            </div>
        </div>
    EOD;

    //TODO:
    Attempt::end($attemptDetails->id);
}