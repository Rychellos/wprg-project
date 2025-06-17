<?php

require_once "database.php";

const QUIZZES_PER_PAGE = 15;

class Quiz
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
     * Summary of getWithCategory
     * @param int $id
     * @return false|array{
     *   0: object{
     *      quizId: number,
     *      name: string
     *  }
     */
    public static function getWithCategory(int $id): bool|array
    {
        $statement = Database::get_connection()->prepare("SELECT quizId, Quiz.name AS name FROM Quiz JOIN QuizCategory ON Quiz.id = QuizCategory.quizId WHERE QuizCategory.categoryId = :id;");
        $statement->execute([
            "id" => $id
        ]);

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int|null $afterId
     * @return false|array{
     *   0: object{
     *      quizId: number,
     *      name: string
     *  }[],
     *  1: bool
     * }
     */
    public static function fetchPage(int $afterId = null): bool|array
    {
        $limit = QUIZZES_PER_PAGE + 1;

        if ($afterId === null) {
            $stmt = self::$connection->prepare("SELECT * FROM Quiz ORDER BY id ASC LIMIT $limit");
            $stmt->execute();
        } else {
            $stmt = self::$connection->prepare("SELECT * FROM Quiz WHERE id < :afterId ORDER BY id ASC LIMIT $limit");
            $stmt->execute(['afterId' => $afterId]);
        }

        $all = $stmt->fetchAll(PDO::FETCH_OBJ);

        $hasMore = count($all) > QUIZZES_PER_PAGE;
        if ($hasMore) {
            array_pop($all);
        }

        return [$all, $hasMore];
    }


    /**
     * @return number | false
     */
    public static function getCount()
    {
        return self::$connection
            ->query("SELECT count(*) FROM Quiz;")
            ->fetchColumn();
    }

    /**
     * @param int $id
     * @return object{
     *  id: int,
     *  name: string,
     *  description: string,
     *  ownerId: int
     * }
     */
    public static function get($id)
    {
        $statement = self::$connection->prepare("SELECT * FROM Quiz WHERE id = :id");
        $statement->execute(["id" => $id]);

        return $statement->fetchObject();
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param string $description
     * @param int $userId
     * @return false|int
     */
    public static function set($id = null, $name, $description, $userId)
    {
        $statement = self::$connection->prepare("Call SetQuiz(:id, :name, :description, :userId)");
        $statement->execute([
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "userId" => $userId
        ]);

        return $statement->fetchColumn();
    }

    /**
     * @param int $quizId
     * @param int $userId
     */
    public static function delete($quizId, $userId)
    {
        //TODO: DELETE ALL CONTENTS

        $statement = self::$connection->prepare("CALL DeleteQuiz(:quizId, :userId);");
        $statement->execute([
            "quizId" => $quizId,
            "userId" => $userId,
        ]);

        return $statement->fetchColumn();
    }

    public static function fetchCategories($quizId)
    {
        $statement = self::$connection->prepare("SELECT Category.name, Category.id FROM Category JOIN QuizCategory ON Category.id = QuizCategory.categoryId WHERE quizId = :quizId");
        $statement->execute([
            "quizId" => $quizId
        ]);

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param int $quizId
     * @param int|int[] $categoryId
     * @param int $userId
     */
    public static function addCategory($quizId, $categoryId, $userId)
    {
        $stmt = self::$connection->prepare("CALL AddQuizCategory(:quizId, :categoryId, :userId)");

        if (is_array($categoryId)) {
            foreach ($categoryId as $currentId) {
                $stmt->execute([
                    ':quizId' => $quizId,
                    ':categoryId' => $currentId,
                    ":userId" => $userId
                ]);
            }
        } else {
            $stmt->execute([
                ':quizId' => $quizId,
                ':categoryId' => $categoryId,
                ":userId" => $userId
            ]);
        }
    }
}

Quiz::initialize();