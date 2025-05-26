<?php
require_once "functions/database.php";
require_once "functions/session.php";

Database::get_connection();

Database::forgetMe(Session::getUserID());

header('Location: ' . "index.php", true, 303);
die();