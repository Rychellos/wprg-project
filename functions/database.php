<?php
require_once "functions/avatar.php";

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
}