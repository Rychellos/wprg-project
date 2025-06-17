<?php
$scripts = array();
require_once "functions/user.php";
require_once "functions/category.php";
require_once "functions/quiz.php";
require_once "functions/session.php";

Session::setLastPage("category.php");

// Autoload components
foreach (glob(__DIR__ . "/components/*.php") as $file) {
    require_once $file;
}

Database::get_connection();
User::checkRememberMe();

if (!Session::isLoggedIn()) {
    header('Location: login.php', true, 303);
    die();
}

if (!Session::isModerator() && !Session::isAdmin()) {
    header('Location: login.php', true, 303);
    Session::setLastPage("index.php");
    die();
}

// Category create
if (isset($_POST["api"], $_POST["name"]) && $_POST["api"] == "create") {
    $createCategoryResult = Category::create($_POST["name"], $_POST["newDescription"] ?? "");
}

if (isset($_GET["id"])) {
    $categoryId = (int) $_GET["id"];
}

// Category edit
if (isset($_POST["api"], $_POST["edit"], $categoryId) && $_POST["api"] == "detail" && is_numeric($categoryId)) {
    $categoryName = $_POST["categoryName"];
    $categoryDescription = $_POST["categoryDescription"];

    if (isset($_POST["categoryName"], $_POST["categoryDescription"])) {
        $categoryUpdated = Category::set($categoryId, $_POST["categoryName"], $_POST["categoryDescription"]);
    }
}

// Category delete
if (isset($_POST["api"], $_POST["delete"], $categoryId) && $_GET["api"] == "detail" && is_numeric($categoryId)) {
    $categoryDeleted = Category::delete($categoryId);
}

// Category read details
if (isset($_GET["api"], $categoryId) && $_GET["api"] == "detail" && is_numeric($categoryId)) {
    $detailData = Category::get($categoryId);

    if (!$detailData) {
        die(json_encode("false"));
    }

    $detailData = [
        "id" => $detailData->id,
        "name" => $detailData->name,
        "description" => $detailData->description,
        "quizzesWithCategory" => Quiz::getWithCategory($categoryId)
    ];

    die(json_encode($detailData));
}

?>

<!DOCTYPE html>
<html class="d-flex w-100 h-100">
<?php head("Zarządzanie kategorriami", ["styles/activeButton.css"]); ?>

<body class="bg-body d-flex h-100 w-100 flex-column">

    <?php navbar(); ?>

    <div class="d-flex flex-column bg-body h-100 overflow-y-auto align-items-stretch">
        <main class="container-fluid bg-body py-3">
            <?php if (Database::has_errored()) {
                require "./errors/databse_connection_error.php";
            } else {
                category();
            } ?>
        </main>
        <?php require "./templates/footer.html"; ?>
    </div>

    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formCreate" class="modal-content needs-validation" method="post">
                <input type="text" name="api" value="create" class="d-none" />

                <div class="modal-header">
                    <h5 class="modal-title">Tworzenie kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body d-flex flex-column gap-3">
                    <?php
                    baseInput(
                        "name",
                        "text",
                        "Nazwa kategorii",
                        "bi-bookshelf",
                        ".+",
                        "Proszę wpisać nową, unikalną nazwę.",
                        $_POST["name"] ?? "",
                        "",
                        true
                    );
                    ?>
                    <div>
                        <label for="newDescription">Opis kategori (opcjonalny)</label>
                        <textarea class="form-control" id="newDescription" name="newDescription"
                            rows="5"><?php $_POST["newDescription"] ?? "" ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Utwórz" />
                </div>
            </form>
        </div>
    </div>

    <?php
    $scripts["scripts/category.js"] = 1;
    $scripts["scripts/validateBootstrapForms.js"] = 1;
    $scripts["scripts/toasts.js"] = 1;

    foreach ($scripts as $key => $value) {
        echo "<script src='$key'></script>";
    }
    ?>

    <?php
    if (isset($createCategoryResult)) {
        if ($createCategoryResult) { ?>
            <script>
                addCategoryModal.show();
                document.getElementById("formCreate").querySelector("input[name=\"name\"]").classList.add("is-invalid")
                console.log("invalidate")
            </script>
        <?php } else { ?>
            <script>
                showToast("Dodano kategorię.", "success")
            </script>
        <?php }
    }
    ?>

    <?php
    if (isset($createCategoryResult)) {
        if ($createCategoryResult) { ?>
            <script>
                addCategoryModal.show();
                document.getElementById("formCreate").querySelector("input[name=\"name\"]").classList.add("is-invalid");
            </script>
        <?php } else { ?>
            <script>
                showToast("Dodano kategorię.", "success")
            </script>
        <?php }
    }

    if (isset($categoryUpdated)) {
        if ($categoryUpdated) { ?>
            <script>
                showToast("Udało się zaktualizować kategorię.", "success")
            </script>
        <?php } else { ?>
            <script>
                showToast("Nie udało się zaktualizować kategorii.", "warning")
            </script>
        <?php }
    } ?>
</body>