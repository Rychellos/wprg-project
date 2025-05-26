<?php

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
     * @return int 0 on success, 1 if username is already taken or 2 if email is used by other account
     */
    public static function createUser($username, $email, $password)
    {
        $connection = self::$connection;

        $statement = $connection->prepare("SELECT CreateUser(:username, :email, :password)");

        $statement->execute([
            "username" => $username,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_ARGON2ID)
        ]);

        return $statement->fetchColumn();
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
     *  type: string
     * }
     */
    public static function loginUser($email, $password)
    {
        $returnValue = (object) [
            "id" => null,
            "name" => null,
            "email" => null,
            "avatarId" => null,
            "type" => null
        ];

        $connection = self::$connection;

        $statement = $connection->prepare("SELECT * FROM User WHERE email = :email;");

        $statement->execute(["email" => $email]);

        $dbReturnValue = $statement->fetchObject();

        if (
            $dbReturnValue &&
            password_verify($password, $dbReturnValue->passwordHash)
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
        // Generate selector and validator
        $selector = bin2hex(random_bytes(6));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expires = date('Y-m-d H:i:s', time() + 172800); // 2 days

        // Store in DB
        $stmt = self::$connection->prepare("INSERT INTO UserToken (userId, selector, hashedValidator, expires) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $selector, $hashedValidator, $expires]);

        // Set cookie
        $cookieValue = $selector . ':' . $validator;
        setcookie('rememberMe', $cookieValue, time() + 172800, '/', '', false, true);
    }

    public static function checkRememberMe()
    {
        if (!Session::getUserID() && isset($_COOKIE['rememberMe'])) {
            [$selector, $validator] = explode(':', $_COOKIE['rememberMe']);

            $stmt = self::$connection->prepare("SELECT * FROM UserToken WHERE selector = ? AND expires >= NOW()");
            $stmt->execute([$selector]);
            $token = $stmt->fetchObject();

            if ($token && hash_equals($token->hashedValidator, hash('sha256', $validator))) {
                // Valid token, log the user in
                // Session::setUserID($token['userId']);
                Session::fetchFromDatabase($token->userId);

                // Optionally refresh token (rotate)
                self::rememberUser($token->userId); // Rotate token

                return true;
            } else {
                // Invalid or expired
                setcookie('rememberMe', '', time() - 3600, '/');
                return false;
            }
        }

        return false;
    }

    public static function forgetMe($userId)
    {
        $stmt = self::$connection->prepare("UPDATE UserToken SET expires=NOW() WHERE userId = ? AND expires >= NOW()");
        $stmt->execute([$userId]);
        Session::destroy();
    }
}
