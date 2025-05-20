<?php

function register($username, $userEmail, $errorCode, $errorMessage)
{
  $userNameValue = "";
  if (isset($username)) {
    $userNameValue = "value='$username'";
  }

  $userEmailValue = "";
  if (isset($userEmail)) {
    $userEmailValue = "value='$userEmail'";
  }

  $messageUsernameInvalid = "Proszę wpisać poprawną nazwę uzytkownika. Należy użyć od 6 do 255 dozwolonych znaków (a-z, A-Z, 0-9, oraz _)";
  $messageEmailInvalid = "Proszę wpisać poprawny adres e-mail.";
  $usernameClass = "";
  $emailClasss = "";

  if (isset($errorCode)) {
    switch ($errorCode) {
      case 1:
        $messageUsernameInvalid = $errorMessage;
        $usernameClass = " is-invalid";
        break;
      case 2:
        $messageEmailInvalid = $errorMessage;
        $emailClasss = " is-invalid";
        break;
    }
  }

  echo <<<EOD
    <div class="container mb-5 h-100">
      <div class="d-flex h-100 col-11 col-md-8 col-lg-7 col-xl-6 m-auto">
        <form
          class="bg-body-secondary my-auto w-100 border border-1 py-3 rounded shadow needs-validation"
          novalidate
          action="register.php"
          method="POST"
          id="registerForm"
        >
          <span class="position-absolute fs-1 ms-3 text-primary-emphasis">
            <i class="bi bi-person-fill"></i>
          </span>

          <h2 class="text-center my-4 text-primary">Rejestracja</h2>

          <div class="col-11 col-lg-10 mx-auto">
            <hr />
          </div>

          <div class="col-10 col-lg-8 mx-auto">
            <div class="input-group has-validation mb-4 shadow">
              <span class="input-group-text rounded-end-0">
                <i class="bi bi-person-fill fs-4 cs-fw"></i>
              </span>
              <div class="form-floating">
                <input
                  required
                  autocomplete="off"
                  type="text"
                  pattern="^[\w]{6,255}$"
                  class="form-control$usernameClass"
                  name="userName"
                  id="userName"
                  placeholder="Nazwa użytkownika"
                  $userNameValue
                />
                <label for="userName">Nazwa użytkownika</label>
                <div class="invalid-tooltip">
                  $messageUsernameInvalid
                </div>
              </div>
            </div>

            <div class="input-group has-validation mb-4 shadow">
              <span class="input-group-text rounded-end-0">
                <i class="bi bi-envelope-fill fs-4 cs-fw"></i>
              </span>
              <div class="form-floating">
                <input
                  required
                  autocomplete="off"
                  type="email"
                  pattern="^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$"
                  class="form-control$emailClasss"
                  name="userEmail"
                  id="userEmail"
                  placeholder="Adres e-mail"
                  $userEmailValue
                />
                <label for="userEmail">Adres e-mail</label>
                <div class="invalid-tooltip">
                  $messageEmailInvalid
                </div>
              </div>
            </div>

            <div class="input-group has-validation mb-3 shadow">
              <span class="input-group-text rounded-end-0">
                <i class="bi bi-unlock-fill fs-4 cs-fw"></i>
              </span>
              <div class="form-floating">
                <input
                  required
                  type="password"
                  class="form-control"
                  name="userPassword"
                  id="userPassword"
                  placeholder="Hasło"
                />
                <label for="userPassword">Hasło</label>
                <div class="invalid-tooltip">Proszę wpisać hasło.</div>
              </div>
            </div>

            <div class="input-group has-validation mb-3 shadow">
              <span class="input-group-text rounded-end-0">
                <i class="bi bi-unlock-fill fs-4 cs-fw opacity-0"></i>
                <i
                  class="bi bi-unlock-fill fs-6 cs-fw position-absolute"
                  style="transform: translateX(0.25rem)"
                ></i>
                <i
                  class="bi bi-arrow-repeat text-muted fs-1 position-absolute"
                  style="transform: translateX(-0.5rem)"
                ></i>
              </span>
              <div class="form-floating">
                <input
                  required
                  type="password"
                  class="form-control"
                  name="userPasswordRepeat"
                  id="userPasswordRepeat"
                  placeholder="Hasło"
                />
                <label for="userPasswordRepeat">Powtórz hasło</label>
                <div class="invalid-tooltip">Hasła nie zgadzają się.</div>
              </div>
            </div>

            <input
              type="submit"
              class="btn btn-primary w-100 my-4 shadow"
              value="Zarejestruj się"
            />

            <div class="text-muted">
              Rejestrując się w naszym serwisie zgadzasz się na panujący tutaj
              <a href="regulamin.php">regulamin</a>.
            </div>
          </div>
        </form>
      </div>
    </div>
  EOD;
}