<?php

require_once "database.php";

const QUESTIONS_PER_PAGE = 15;
const QUESTION_TYPES = ["guessFromImage", "textareaAnswers", "singleChoice", "multipleChoice", "fillTheGaps"];
const QUESTION_TYPES_TEXT = ["Zgadnij z obrazka", "Odpowiedź pisemna", "Pojedyńczego wyboru", "Wielokrotnego wyboru", "Wypełnij luki"];
const QUESTION_TYPES_TEXT_ENABLED = [false, true, true, true, false];

class Question
{
    /** 
     * @var PDO 
     * */
    private static $connection;

    public static function initialize()
    {
        if (self::$connection) {
            return;
        }

        self::$connection = Database::get_connection();
    }

    /**
     * @return int | false
     */
    public static function getCount()
    {
        $statement = self::$connection->prepare("SELECT count(*) FROM Question;");
        $statement->execute();

        return $statement->fetchColumn();
    }

    /**
     * @param int|null $questionId
     * @param int $quizId
     * @param 'guessFromImage'|'textareaAnswers'|'singleChoice'|'multipleChioce'|'fillTheGaps' $type
     * @param int $userId
     * @return bool
     */
    public static function set($questionId = null, $quizId, $type, $userId)
    {
        $statement = self::$connection->prepare("CALL SetQuestion(:questionId, :quizId, :type, :userId);");

        return $statement->execute([
            "questionId" => $questionId,
            "quizId" => $quizId,
            "type" => $type,
            "userId" => $userId,
        ]);
    }


    /**
     * @param int $quizId
     * @param int $questionId
     * @return false|object{
     *    id: int,
     *    quizId: int,
     *    type: 'guessFromImage'|'textareaAnswers'|'singleChoice'|'multipleChioce'|'fillTheGaps'
     * }
     */
    public static function get($questionId)
    {
        $statement = self::$connection->prepare("SELECT * FROM Question WHERE id=:questionId");
        $statement->execute([
            "questionId" => $questionId
        ]);

        return $statement->fetchObject();
    }

    public static function delete($questionId, $userId)
    {
        //TODO: CASCADE DELETE CONTENTS

        $statement = self::$connection->prepare("CALL DeleteQuestion(:questionId, :userId);");

        return $statement->execute([
            "questionId" => $questionId,
            "userId" => $userId,
        ]);
    }

    /**
     * @param int $quizId
     * @param int|null $afterId
     * @return array{0: object{
     *    id: int,
     *    quizId: int,
     *    type: 'guessFromImage'|'textareaAnswers'|'singleChoice'|'multipleChioce'|'fillTheGaps'
     * }[], 1: bool}
     */
    public static function fetchPage($quizId, $afterId = null)
    {
        $limit = QUESTIONS_PER_PAGE + 1;

        if ($afterId === null) {
            $stmt = self::$connection->prepare("SELECT * FROM Question WHERE quizId=:quizId ORDER BY id ASC LIMIT $limit");
            $stmt->execute(["quizId" => $quizId]);
        } else {
            $stmt = self::$connection->prepare("SELECT * FROM Question WHERE quizId=:quizId AND id > :afterId ORDER BY id ASC LIMIT $limit");
            $stmt->execute(["quizId" => $quizId, "afterId" => $afterId]);
        }

        $all = $stmt->fetchAll(PDO::FETCH_OBJ);

        $hasMore = count($all) > CATEGORIES_PER_PAGE;
        if ($hasMore) {
            array_pop($all);
        }

        return [$all, $hasMore];
    }

    /**
     * @param int $contentId
     * @param int $questionId
     * @param string $textContent
     * @param string $imageSrc
     * @param string $videoSrc
     * @param int $userId
     * @return bool
     */
    public static function setContents($contentId, $questionId, $textContent, $imageSrc, $videoSrc, $userId)
    {
        $statement = self::$connection->prepare("
        CALL SetQuestionContent(:contentId, :questionId, :textContent, :imageSrc, :videoSrc, :userId);
    ");

        return $statement->execute([
            "contentId" => $contentId,
            "questionId" => $questionId,
            "textContent" => $textContent,
            "imageSrc" => $imageSrc,
            "videoSrc" => $videoSrc,
            "userId" => $userId,
        ]);
    }

    /**
     * @param int $questionId
     * @return object{
     *    id: int,
     *    questionId: int,
     *    textContent: string,
     *    imageSrc: string,
     *    videoSrc: string
     * }[]
     */
    public static function fetchContents(int $questionId)
    {
        $stmtContent = self::$connection->prepare("SELECT * FROM QuestionContent WHERE questionId = :questionId");
        $stmtContent->execute(['questionId' => $questionId]);
        return $stmtContent->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $anwserId
     * @param int $questionId
     * @param string $textContent
     * @param string $imageSrc
     * @param string $videoSrc
     * @param bool $isValid
     * @param int $userId
     * @return bool
     */
    public static function setAnwser($anwserId, $questionId, $textContent, $imageSrc, $videoSrc, $isValid, $userId)
    {
        $statement = self::$connection->prepare("CALL SetQuestionAnswer(:anwserId, :questionId, :textContent, :imageSrc, :videoSrc, :isValid, :userId)");

        return $statement->execute([
            "anwserId" => $anwserId,
            "questionId" => $questionId,
            "textContent" => $textContent,
            "imageSrc" => $imageSrc,
            "videoSrc" => $videoSrc,
            "isValid" => $isValid ? 1 : 0,
            "userId" => $userId
        ]);
    }

    /**
     * @param int $questionId
     * @return object{
     *    id: int,
     *    questionId: int,
     *    textContent: string,
     *    imageSrc: string,
     *    videoSrc: string,
     *    isValid: '0'|'1'
     * }[]
     */
    public static function fetchAnswers(int $questionId)
    {
        $stmtContent = self::$connection->prepare("SELECT * FROM QuestionAnswers WHERE questionId = :questionId");
        $stmtContent->execute(['questionId' => $questionId]);
        return $stmtContent->fetchAll(PDO::FETCH_OBJ);
    }
}

Question::initialize();