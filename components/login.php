<?php

function login($email, $errorCode, $errorMessage)
{
    echo <<<EOD
    <div class="container mb-5 h-100">
        <div class="d-flex h-100 col-11 col-md-8 col-lg-7 col-xl-6 m-auto">
            <form
            class="bg-body-secondary my-auto w-100 border border-1 py-3 rounded shadow needs-validation"
            novalidate
            action="login.php"
            method="POST"
            >
                <span
                    class="position-absolute fs-1 ms-3 text-primary-emphasis"
                    style="rotate: 25deg"
                >
                    <i class="bi bi-key-fill"></i>
                </span>

                <h2 class="text-center my-4 text-primary">Logowanie</h2>

                <div class="col-11 col-lg-10 mx-auto">
                    <hr />
                </div>

                <div class="col-10 col-lg-8 mx-auto gap-3 d-flex flex-column">
    EOD;

    baseInput(
        "userEmail",
        "email",
        "Adres e-mail",
        "bi-envelope-fill",
        "^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$",
        "Proszę wpisać poprawny adres e-mail.",
        $email,
        "",
        true,
        true
    );

    baseInput(
        "userPassword",
        "password",
        "Hasło",
        "bi-unlock-fill",
        ".+",
        $errorCode ? $errorMessage : "Proszę wpisać hasło.",
        "",
        $errorCode ? "is-invalid" : "",
        true,
        false
    );

    echo <<<EOD
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="rememberMe" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Zapamiętaj mnie
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 my-3 shadow">
                        Zaloguj
                    </button>

                    <div class="text-muted">
                        Lub zarejestruj się
                        <a href="register.php">tutaj</a>
                    </div>
    EOD;

    echo <<<EOD
                </div>
            </form>
        </div>
    </div>
    EOD;
}