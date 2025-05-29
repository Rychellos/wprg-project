<?php
session_start();

class Session
{
    public static function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Fetches data to session as in user logged in by password
     * @param int $userId
     * @return void
     */
    public static function fetchFromDatabase($userId)
    {
        $user = Database::getUserInfo($userId);

        Session::setIsLoggedIn(true);
        Session::setIsModerator($user->type == "moderator");
        Session::setIsAdmin($user->type == "administrator");
        Session::setUserID($user->id);
        Session::setUserEmail($user->email);
        Session::setUserName($user->name);
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION["isLoggedIn"]) ?? $_SESSION["isLoggedIn"];
    }

    public static function setIsLoggedIn($value)
    {
        $_SESSION["isLoggedIn"] = $value;
    }

    public static function isModerator()
    {
        return isset($_SESSION["isModerator"]) ?? $_SESSION["isModerator"];
    }

    public static function setIsModerator($value)
    {
        $_SESSION["isModerator"] = $value;
    }

    public static function isAdmin()
    {
        return isset($_SESSION["isAdmin"]) ?? $_SESSION["isAdmin"];
    }

    public static function setIsAdmin($value)
    {
        $_SESSION["isAdmin"] = $value;
    }

    public static function getUserID()
    {
        return isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
    }

    public static function setUserID($value)
    {
        $_SESSION["user_id"] = $value;
    }

    public static function getUserEmail()
    {
        return isset($_SESSION["user_email"]) ? $_SESSION["user_email"] : null;
    }

    public static function setUserEmail($value)
    {
        $_SESSION["user_email"] = $value;
    }

    public static function getUserName()
    {
        return isset($_SESSION["user_name"]) ? $_SESSION["user_name"] : null;
    }

    public static function setUserName($value)
    {
        $_SESSION["user_name"] = $value;
    }

    public static function getLastPage()
    {
        return isset($_SESSION["lastPage"]) ? $_SESSION["lastPage"] : null;
    }

    public static function setLastPage($value)
    {
        $_SESSION["lastPage"] = $value;
    }
}