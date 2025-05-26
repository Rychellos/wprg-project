<?php require_once("functions/database.php") ?>

<section class="d-flex h-100 w-100">
  <div class="alert alert-danger m-auto" role="alert">
    <h4 class="alert-heading">
      <i class="bi bi-database-exclamation me-1"></i>
      Wystąpił błąd!
    </h4>
    <p>Nie udało się nawiązać połączenia z bazą danych.</p>
    <hr />
    <p class="mb-0">
      Skontaktuj się z administratorem aby rozwiązać ten problem.
    </p>
    <?php
    if (Database::has_errored() == 2) { ?>
      <details>
        <summary>Wyświetl szczegóły</summary>
        <div>
          <?php var_dump(Database::get_connection()->errorInfo()) ?>
        </div>
      </details>
    <?php } ?>
  </div>
  </sectio>