<?php
require_once 'functions/database.php';
require_once 'functions/session.php';
require_once 'functions/avatar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatarFile'])) {
    // Step 1: Verify user is logged in
    if (!Session::isLoggedIn()) {
        echo "Nie uwierzytelniono.";
        header("", true, 401);
        die();
    }

    $connection = Database::get_connection();

    $userId = Session::getUserId();
    $file = $_FILES['avatarFile'];

    // Step 2: Validate upload
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        echo "Nie udało się przesłać obrazu.";
        header("", true, 500);
        die();
    }

    // Step 3: Check MIME type
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        echo "Nieprawidłowy format obrazu.";
        header("", true, 400);
        die();
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imageInfo['mime'], $allowedTypes)) {
        echo "Nieobsługiwany format obrazu.";
        header("", true, 400);
        die();
    }

    // Step 4: Get or generate avatar hash
    $url = Database::getProfilePictureUrl($userId);

    if (!$url) {
        // First-time upload → generate hash and store in DB
        $hash = hash('sha256', $userId . microtime(true));
        $filename = $hash . '.png';

        // Save DB reference
        $stmt = $connection->prepare("INSERT INTO UserAvatar (url, userId) VALUES (?, ?)");
        $stmt->execute([$filename, $userId]);
    } else {
        // Use existing hash
        $filename = basename($url);
    }

    // Step 5: Save image as PNG using GD
    $success = Avatar::writeHashed($filename, $file['tmp_name']);
    if ($success) {
        echo "Pomyślnie zmieniono zdjęcie profilowe.";
        header("", true, 200);
        die();
    } else {
        echo "Coś poszło nie tak :(";
        header("", true, 500);
        die();
    }
} else {
    echo $_SERVER["REQUEST_METHOD"] . " | " . var_dump($_FILES);
    echo "Nie udało się przesłać obrazu.";
    header("", true, 500);
    die();
}