<?php
$scripts = array();
require_once "functions/user.php";
require_once "functions/session.php";

Session::setLastPage("aboutMe.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn() || !Session::isAdmin()) {
    header('Location: ' . "login.php", true, 303);
    die();
}

$events = [
    1 => 'Rejestracja użytkownika',
    2 => 'Logowanie użytkownika',
    3 => 'Wylogowanie użytkownika',
    4 => 'Utworzono token użytkownika',
    5 => 'Token użytkownika wygasł',
    6 => 'Utworzono quiz',
    7 => 'Zaktualizowano quiz',
    8 => 'Usunięto quiz',
    9 => 'Rozpoczęto próbę rozwiązania quizu',
    10 => 'Złożono próbę rozwiązania quizu',
    11 => 'Przypisano quiz do kategorii',
    12 => 'Usunięto przypisanie quizu do kategorii',
    13 => 'Utworzono pytanie',
    14 => 'Zaktualizowano pytanie',
    15 => 'Usunięto pytanie',
    16 => 'Utworzono odpowiedź do pytania',
    17 => 'Zaktualizowano odpowiedź do pytania',
    18 => 'Usunięto odpowiedź do pytania',
    19 => 'Przesłano odpowiedź użytkownika',
    20 => 'Zaktualizowano odpowiedź użytkownika',
    21 => 'Usunięto odpowiedź użytkownika',
    22 => 'Utworzono kategorię',
    23 => 'Zaktualizowano kategorię',
    24 => 'Usunięto kategorię',
    25 => 'Zaktualizowano awatar użytkownika',
    26 => 'Zmieniono rolę użytkownika',
    27 => 'Dodano ręczny wpis w logach',
    28 => 'Utworzono treść pytania',
    29 => 'Zaktualizowano treść pytania',
    30 => 'Usunięto treść pytania'
];
?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Dziennik Zdarzeń"); ?>

<body class="bg-body d-flex h-100 w-100 flex-column overflow-hidden">
    <?php navbar(); ?>

    <div class="d-flex flex-column h-100 overflow-y-auto">
        <main class="container-fluid mb-3">

            <?php Database::get_connection();

            if (Database::has_errored()) {
                require "errors/databse_connection_error.php";
            } else {
                $logs = Database::get_connection()->query("SELECT User.name, actionId, timestamp, detail FROM LogUserAction JOIN User ON LogUserAction.userId = User.id ORDER BY LogUserAction.id DESC")->fetchAll(PDO::FETCH_OBJ);

                echo "<h1>Dziennik zdarzeń</h1>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Czas</th><th>Użytkownik</th><th>Akcja</th><th>Detale</th></tr></thead><tbody>";
                foreach ($logs as $log) {
                    echo <<<EOD
                        <tr>
                            <td>{$log->timestamp}</td>
                            <td>{$log->name}</td>
                            <td>{$events[$log->actionId]}</td>
                            <td>{$log->detail}</td>
                        </tr>
                    EOD;
                }
                echo "</tbody></table>";
            }

            ?>

        </main>
        <?php require "./templates/footer.html"; ?>
    </div>

    <?php
    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>
</body>

</html>