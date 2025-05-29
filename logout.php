<?php
require_once "functions/database.php";
require_once "functions/session.php";

Database::get_connection();

Database::forgetMe(Session::getUserID());

if (Session::getUserID()) {
    Session::destroy();
}

header('Location: ' . "index.php", true, 303);
die();