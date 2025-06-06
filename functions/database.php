<?php
require_once "functions/avatar.php";

const CATEGORIES_PER_PAGE = 15;

class Database
{
    private static $connection;

    public static function get_connection()
    {
        if (self::$connection == null) {
            try {
                self::$connection = new PDO("mysql:host=localhost;dbname=quiz;", "quiz", "quiz");
            } catch (PDOException $e) {
            }
        }

        return self::$connection;
    }

    public static function has_errored()
    {
        if (!isset(self::$connection)) {
            return 1;
        }

        if (str_starts_with(self::$connection->errorCode(), "00")) {
            return 0;
        }

        if (self::$connection->errorCode()) {
            return 2;
        }

        return 0;
    }

    /**
     * Creates user with given data;
     * @param string $username
     * @param string $email
     * @param string $password
     * @return int 0 - ok; 1 - username is already taken; 2 - email is used by other account
     */
    public static function createUser($username, $email, $password)
    {
        $connection = self::$connection;

        $statement = $connection->prepare("CALL CreateUser(:username, :email, :passwordHash, @result);");
        $statement->execute([
            "username" => $username,
            "email" => $email,
            "passwordHash" => password_hash($password, PASSWORD_ARGON2ID)
        ]);
        $statement->closeCursor();

        $resultStatement = $connection->query("SELECT @result;");
        $result = $resultStatement->fetchColumn();

        return (int) $result;
    }


    /**
     * Checks if user of given email and password exists
     * @param string $email
     * @param string $password
     * @return object{
     *  id: number,
     *  name: string,
     *  email: number,
     *  avatarId: number,
     *  type: string,
     *  resultCode: number
     * } //Result codes: 0-ok; 1-invalid
     */
    public static function loginUser($email, $password)
    {
        // Step 1: Fetch user by email
        $stmt = self::$connection->prepare("CALL LoginUser(:email)");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetchObject();

        $stmt->closeCursor(); // Important: free result for next query

        $returnValue = (object) [
            "resultCode" => null,
            "id" => null,
            "name" => null,
            "email" => $email,
            "avatarId" => null,
            "type" => null
        ];

        if (!$user) {
            // No such email
            $logStmt = self::$connection->prepare("CALL LogLoginAttempt(NULL, :detail)");
            $logStmt->execute(['detail' => "Login failed: unknown email $email"]);
            $returnValue->resultCode = 1;
            return $returnValue;
        }

        // Step 2: Verify password
        if (!password_verify($password, $user->passwordHash)) {
            $logStmt = self::$connection->prepare("CALL LogLoginAttempt(:userId, :detail)");
            $logStmt->execute([
                'userId' => $user->id,
                'detail' => 'Login failed: invalid password'
            ]);
            $returnValue->resultCode = 2;
            return $returnValue;
        }

        // Step 3: Successful login
        $logStmt = self::$connection->prepare("CALL LogLoginAttempt(:userId, NULL)");
        $logStmt->execute(['userId' => $user->id]);

        $returnValue->resultCode = 0;
        $returnValue->id = $user->id;
        $returnValue->name = $user->name;
        $returnValue->avatarId = $user->avatarId;
        $returnValue->type = $user->type;

        return $returnValue;
    }


    /**
     * Checks if user of given email and password exists and if do, returns their details
     * @param number $id
     * @return object{
     *  id: number,
     *  name: string,
     *  email: number,
     *  avatarId: number,
     *  type: string
     * }
     */
    public static function getUserInfo($id)
    {
        $returnValue = (object) [
            "id" => null,
            "name" => null,
            "email" => null,
            "avatarId" => null,
            "type" => null
        ];

        $connection = self::$connection;

        $statement = $connection->prepare("SELECT * FROM User WHERE id = :id;");

        $statement->execute(["id" => $id]);

        $dbReturnValue = $statement->fetchObject();

        if (
            $dbReturnValue
        ) {
            $returnValue->id = $dbReturnValue->id;
            $returnValue->name = $dbReturnValue->name;
            $returnValue->email = $dbReturnValue->email;
            $returnValue->avatarId = $dbReturnValue->avatarId;
            $returnValue->type = $dbReturnValue->type;
        }

        return $returnValue;
    }

    /**
     * Remembers user for future auto login
     * @param number $userId
     * @return void
     */
    public static function rememberUser($userId)
    {
        $selector = bin2hex(random_bytes(6));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expires = date('Y-m-d H:i:s', time() + 172800); // 2 days

        $statement = self::$connection->prepare("CALL RememberUser(:userId, :selector, :hashedValidator, :expires);");
        $statement->execute([
            "userId" => $userId,
            "selector" => $selector,
            "hashedValidator" => $hashedValidator,
            "expires" => $expires
        ]);

        $cookieValue = $selector . ':' . $validator;
        setcookie('rememberMe', $cookieValue, time() + 172800, '/', '', false, true);
    }


    public static function checkRememberMe()
    {
        if (!Session::getUserID() && isset($_COOKIE['rememberMe'])) {
            [$selector, $validator] = explode(':', $_COOKIE['rememberMe']);

            $stmt = self::$connection->prepare("CALL CheckRememberMe(:selector)");
            $stmt->execute(['selector' => $selector]);
            $token = $stmt->fetchObject();
            $stmt->closeCursor();

            if (
                $token &&
                is_null($token->expired) &&
                hash_equals($token->hashedValidator, hash('sha256', $validator))
            ) {
                Session::fetchFromDatabase($token->userId);

                // Renew token
                self::rememberUser($token->userId);

                return true;
            } else {
                // Mark as expired
                $expireStmt = self::$connection->prepare(
                    "UPDATE UserToken SET expired = NOW() WHERE selector = :selector"
                );
                $expireStmt->execute(['selector' => $selector]);

                // Expire cookie
                setcookie('rememberMe', '', time() - 3600, '/', '', false, true);
                return false;
            }
        }

        return false;
    }


    public static function forgetMe($userId)
    {
        if (isset($_COOKIE['rememberMe'])) {
            [$selector, $validator] = explode(':', $_COOKIE['rememberMe']);
            setcookie('rememberMe', '', time() - 3600, '/', '', false, true);

            $statement = self::$connection->prepare("CALL ForgetMe(:userId, :selector);");
            $statement->execute([
                "userId" => $userId,
                "selector" => $selector
            ]);
        }
    }


    public static function getProfilePictureUrl($userId): string
    {
        $statement = self::$connection->prepare("SELECT url FROM UserAvatar WHERE userId = ?;");
        $statement->execute([$userId]);

        $url = $statement->fetchColumn();

        return $url ? Avatar::$directory . $url : "https://placehold.co/256x256.png";
    }

    /**
     * @param number $page
     * @return object{
     *  id: number,
     *  name: string,
     *  description: string,
     * }[]
     */
    public static function fetchCategories($page)
    {
        if (!is_numeric($page)) {
            return [];
        }

        $page *= CATEGORIES_PER_PAGE;

        $statement = self::$connection->prepare("SELECT * FROM Category LIMIT " . CATEGORIES_PER_PAGE . " OFFSET $page;");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return number | false
     */
    public static function getCategoriesCount()
    {
        $statement = self::$connection->prepare("SELECT count(*) FROM Category;");
        $statement->execute();

        return $statement->fetchColumn();
    }

    /**
     * @param number $id
     * @return object{
     *  id: number,
     *  name: string,
     *  description: string,
     * }|false
     */
    public static function getCategory($id)
    {
        $statement = self::$connection->prepare("SELECT * FROM Category WHERE id = :id;");
        $statement->execute(["id" => $id]);

        return $statement->fetchObject();
    }

    /**
     * @param number $id
     * @param string $name
     * @param string $description
     * @return bool
     */
    public static function setCategory($id, $name, $description)
    {
        $statement = self::$connection->prepare("UPDATE Category SET name=:name, description=:description WHERE id = :id;");
        return $statement->execute([
            "id" => $id,
            "name" => $name,
            "description" => $description
        ]);
    }

    /**
     * @param string $name
     * @param string $description
     * @return int 0 on success, 1 if category of this name exisits
     */
    public static function createCategory($name, $description)
    {
        $statement = self::$connection->prepare("Select CreateCategory(:name, :description);");
        $statement->execute([
            "name" => $name,
            "description" => $description
        ]);

        return $statement->fetchColumn();
    }

    /**
     * @param int $id
     */
    public static function deleteCategory($id)
    {
        $statement = self::$connection->prepare("Select DeleteCategory(:id);");
        $statement->execute([
            "id" => $id
        ]);

        return $statement->fetchColumn();

    }

    /**
     * @param number $id
     * @return false|object{
     *   quizId: number,
     *   name: string
     * }[]
     */
    public static function getQuizzesWithCategory($id)
    {
        $statement = self::$connection->prepare("SELECT quizId, Quiz.name AS name FROM Quiz JOIN QuizCategory ON Quiz.id = QuizCategory.quizId WHERE QuizCategory.categoryId = :id;");
        $statement->execute([
            "id" => $id
        ]);

        return $statement->fetchAll();
    }
}
