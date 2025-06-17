<?php
require_once "functions/database.php";

class Attempt
{
    /** @var PDO */
    private static $connection;

    public static function initialize()
    {
        self::$connection = Database::get_connection();
    }

    public static function getCount()
    {
        $statement = self::$connection->prepare("SELECT count(*) FROM QuizAttempt;");
        $statement->execute();
    }

    /**
     * Retrieves an active quiz attempt for the specified user.
     *
     * @param int|string $userId The unique identifier of the user.
     * @return false|object{
     *      id: int,
     *      quizId: int,
     *      started: string, // Format: Y-m-d H:i:s
     *      ended: string|null, // Format: Y-m-d H:i:s lub NULL, jeśli próba nie jest zakończona
     *      userId: int|null
     *  }
     */
    public static function get($userId)
    {
        $statement = self::$connection->prepare("SELECT * FROM QuizAttempt WHERE userId=:userId AND ended IS NULL;");
        $statement->execute(["userId" => $userId]);
        return $statement->fetchObject();
    }

    public static function start($userId, $quizId)
    {
        $statement = self::$connection->prepare("CALL StartAttempt(:quizId, :userId);");
        $statement->execute([
            "userId" => $userId,
            "quizId" => $quizId
        ]);
    }

    public static function end($attemptId)
    {
        $statement = self::$connection->prepare("CALL EndAttempt(:attemptId);");
        $statement->execute([
            "attemptId" => $attemptId
        ]);
    }

    /**
     * @param $attemptId
     * @return false|object{
     *   id: int,
     *   quizId: int,
     *   type: string|string|string|string|
     * }
     */
    public static function getNextQuestion($attemptId)
    {
        $statement = self::$connection->prepare("SELECT UA.* FROM UserAnswer UA LEFT JOIN UserAnswerContent UAC ON UA.id = UAC.userAnswerId WHERE UA.attemptId = :attemptId AND UAC.id IS NULL LIMIT 1;");
        $statement->execute([
            "attemptId" => $attemptId
        ]);

        return Question::get($statement->fetchObject()->questionId);
    }

    public static function getAnswerId($attemptId, $questionId)
    {
        $statement = self::$connection->prepare("SELECT id FROM UserAnswer WHERE attemptId=:attemptId AND questionId=:questionId;");
        $statement->execute([
            "attemptId" => $attemptId,
            "questionId" => $questionId
        ]);
        return $statement->fetchColumn();
    }

    public static function addAnswerContent($answerId, $textContent, $numericContent)
    {
        //TODO: Edycja?
        $statement = self::$connection->prepare("CALL AddAnswerContent(:answerId, :textContent, :numericContent);");
        return $statement->execute([
            "answerId" => $answerId,
            "textContent" => $textContent,
            "numericContent" => $numericContent,
        ]);
    }
}

Attempt::initialize();