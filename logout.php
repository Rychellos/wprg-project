<?php
require_once "functions/user.php";
require_once "functions/session.php";

Database::get_connection();

User::forgetMe(Session::getUserID());

if (Session::getUserID()) {
    Session::destroy();
}

header('Location: ' . "index.php", true, 303);
die();