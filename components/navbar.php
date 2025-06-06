<?php
require_once "functions/session.php";

function navbar()
{
    $quizMenuContents = "";
    $accountMenuContents = "";

    if (Session::isLoggedIn()) {
        $quizMenuContents .= <<<EOD
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-check2 cs-fw me-1"></i>
                    Ukończone
                </a>
            </li>
        EOD;

        $accountMenuContents .= <<<EOD
            <li>
                <a class="dropdown-item" href="aboutMe.php">
                    <i class="bi bi-clipboard-fill cs-fw me-1"></i>
                    O mnie
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-graph-up cs-fw me-1"></i>
                    Zobacz statystyki
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="logout.php">
                    <i class="bi bi-lock-fill cs-fw me-1"></i>
                    Wyloguj się
                </a>
            </li>
        EOD;
    } else {
        $accountMenuContents .= <<<EOD
            <li>
                <a class="dropdown-item" href="login.php">
                    <i class="bi bi-key-fill cs-fw me-1"></i>
                    Zaloguj się
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="register.php">
                    <i class="bi bi-person-fill cs-fw me-1"></i>
                    Zajerestruj się
                </a>
            </li>
        EOD;
    }

    if (
        Session::isModerator() ||
        Session::isAdmin()
    ) {
        $quizMenuContents .= <<<EOD
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="quizCreate.php">
                    <i class="bi bi-plus-circle-fill cs-fw me-1"></i>
                    Utwórz
                </a>
            </li>
        EOD;
    }

    if (Session::isAdmin()) {
        $quizMenuContents .= <<<EOD
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-graph-up cs-fw me-1"></i>    
                    Statystyki
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="category.php">
                    <i class="bi bi-stickies-fill cs-fw me-1"></i>
                    Kategorie
                </a>
            </li>
        EOD;
    }

    echo <<<EOD
        <header class="bg-primary-subtle shadow-lg">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid align-items-center d-flex">
                    <a class="navbar-brand primary-secondary-gradient" href="">Quiz Serwis</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="">
                                    <i class="bi bi-house-fill me-1"></i>
                                    Strona Główna
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-check2-square cs-fw me-1"></i>
                                    Quiz
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-calendar-event-fill cs-fw me-1"></i>
                                        Codzienny
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-list cs-fw me-1"></i>
                                        Lista
                                    </a></li>
                                    {$quizMenuContents}
                                </ul>
                            </li>

                            <li class="nav-item mx-auto d-none d-lg-inline"></li>

                            <li class="nav-item">
                                <div class="dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-fill cs-fw me-1"></i>
                                        Konto
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        {$accountMenuContents}
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item my-auto">
                                <button id="nav_theme_toggler" class="bg-transparent border-0 p-0 mx-lg-2 btn text-start invisible theme-toggler light">
                                    <div class="dark">
                                        <i class="bi bi-moon-fill cs-fw me-1"></i>
                                        Tryb ciemny
                                    </div>
                                    <div class="light">
                                        <i class="bi bi-sun-fill fs-fw me-1"></i>
                                        Tryb jasny
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    EOD;
}

$scripts["scripts/theme_toggle.js"] = 1;
$scripts["scripts/nav_theme_toggler.js"] = 1;
