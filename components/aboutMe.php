<?php
function summary()
{
    $connection = Database::get_connection();

    if (Database::has_errored()) {
        include "errors/databse_connection_error.php";
        return;
    }

    $username = Session::getUsername();
    $email = Session::getUserEmail();
    $userType = "użytkownik";

    if (Session::isModerator()) {
        $userType = "moderator";
    }

    if (Session::isAdmin()) {
        $userType = "administrator";
    }

    $profileAvatar = Database::getProfilePictureUrl(Session::getUserID());

    echo <<<EOD
        <div class="p-3 rounded bg-secondary text-dark shadow">
            <div class="container row">
                <div class="col-3 col-lg-2 col-xl-2">
                    <div class="ratio ratio-1x1">
                        <img id="profilePicture" src="$profileAvatar" class="img-fluid rounded rounded-5 w-100" />
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-lg-9 col-xl-10">
                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 fw-semibold">Nazwa konta</div>
                        <div class="col-1 d-none d-sm-block">:</div>
                        <div class="col-12 col-sm-5 col-md-7 col-lg-8 col-xl-9 text-truncate" style="max-width: 100%;">
                            <span>$username</span>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 fw-semibold">Typ konta</div>
                        <div class="col-1 d-none d-sm-block">:</div>
                        <div class="col-12 col-sm-5 col-md-7 col-lg-8 col-xl-9">$userType</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 fw-semibold">Email</div>
                        <div class="col-1 d-none d-sm-block">:</div>
                        <div class="col-12 col-sm-5 col-md-7 col-lg-8 col-xl-9 text-truncate" style="max-width: 100%;">
                            <span>$email</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    EOD;
}

function editProfilePicture()
{
    echo <<<EOD
    <div class="p-3 rounded bg-body-tertiary shadow">
        <div class="container row mx-0 px-0">
            <form id="profilePitureForm" action="uploadAvatar.php" class="col-12 col-md-10 col-lg-6 col-xl-4" method="post" enctype="multipart/form-data">
                <label for="avatarFile" class="form-label">Zmień zdjęcie profilowe</label>
                <input class="form-control mb-3" type="file" id="avatarFile" name="avatarFile" accept="image/*" required />
                <input type="submit" value="Zatwierdź zmianę" class="btn btn-secondary" />
            </form>
        </div>
    </div>
EOD;
}

function changePassword()
{
    echo <<<EOD
    <div class="p-3 rounded bg-body-tertiary shadow">
        <div class="container row mx-0 px-0">
            <form class="col-12 col-md-10 col-lg-6 col-xl-4" method="post">
                <div class="mb-3">
                    <label for="currentPassword" class="form-label">Obecne hasło</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="form-label">Nowe hasło</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>

                <div class="mb-3">
                    <label for="newPasswordRepeat" class="form-label">Potwierdź nowe hasło</label>
                    <input type="password" class="form-control" id="newPasswordRepeat" name="newPasswordRepeat" required>
                </div>

                <input type="submit" class="btn btn-secondary" value="Zatwierdź zmianę" />
          </form>
        </div>
    </div>
EOD;
}

function export()
{
    echo <<<EOD
    <form class="p-3 rounded bg-body-tertiary shadow" action="./aboutMe.php" method="post">
        <label class="mb-3">Moje osiągnięcia</label>
        <br />
        <input type="submit" value="Wyeksportuj swoje osiągnięcia" class="btn btn-secondary" />
        <input type="checkbox" name="export" checked class="visually-hidden" />
    </form>
EOD;
}

function aboutMe()
{
    echo <<<EOD
        <div class="container d-flex flex-column gap-4">
            <div class="container d-flex py-2 flex-column gap-3">
                
    EOD;

    summary();

    editProfilePicture();

    changePassword();

    export();

    echo <<<EOD
            </div>
        </div>
    EOD;
}