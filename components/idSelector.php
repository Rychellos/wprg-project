<?php

function renderKeysetPagination(?int $afterId, ?int $nextAfterId, string $currentUrl): string
{
    $output = '';

    // Previous = show without any afterIndex (go to first page)
    $previousUrl = $currentUrl;
    $nextUrl = $nextAfterId ? "$currentUrl?afterIndex=$nextAfterId" : '#';

    // We disable "next" if there's no next cursor
    $prevDisabled = $afterId === null ? 'disabled' : '';
    $nextDisabled = $nextAfterId === null ? 'disabled' : '';

    $output .= <<<HTML
        <li class="page-item $prevDisabled"><a class="page-link" href="$previousUrl" aria-label="Previous">&laquo; &laquo;</a></li>
        <li class="page-item disabled"><span class="page-link" href="$previousUrl" aria-label="Current">Nawigacja</span></li>
        <li class="page-item $nextDisabled"><a class="page-link" href="$nextUrl" aria-label="Next">&raquo; &raquo;</a></li>
    HTML;

    return $output;
}

/**
 * @param object{id: int, name: string}[] $items
 * @param bool $hasMore
 * @param int $afterId
 * @param int $nextAfterId
 * @param string $title
 * @param string $identifires
 * @param int $currentId
 * @return void
 */
function idSelector($items, $hasMore, $afterId, $nextAfterId, $title, $identifier, $currentId = -1)
{
    global $scripts;
    $scripts["scripts/idSelector.js"] = 1;

    $currentUrl = strtok($_SERVER["REQUEST_URI"], '?');

    echo <<<EOD
        <div class="w-100 sticky-top">
            <h5 class="d-flex align-items-center">
                {$title}
                <button id="{$identifier}AddButton" class="btn ms-auto">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </h5>

            <nav class="ms-3" aria-label="$title page navigation">
                <ul class="pagination">
    EOD;

    echo renderKeysetPagination($afterId, $hasMore ? $nextAfterId : null, $currentUrl);

    echo <<<EOD
                </ul>
            </nav>
            <hr />

            <form action="void(0)" name="idSelectorForm" class="d-flex flex-column">
                <select autocomplete="off" name="idSelector" id="idSelector" class="form-select d-md-none">
    EOD;

    foreach ($items as $item) {
        $selected = ($item->id == $currentId) ? 'selected' : '';
        echo "<option value=\"{$item->id}\" $selected>{$item->name}</option>";
    }

    echo <<<EOD
                </select>
                <ul class="list-group list-group-flush rounded px-2 d-none d-md-block overflow-y-auto">
    EOD;

    foreach ($items as $item) {
        $checked = ($item->id == $currentId) ? 'checked' : '';
        echo <<<EOD
            <li class="list-group-item d-flex gap-2 bg-transparent w-100">
                <input type="radio" name="idSelector" class="btn-check" value="{$item->id}" id="idSelector-{$item->id}" $checked>
                <label class="btn w-100 text-start text-truncate" for="idSelector-{$item->id}">{$item->name}</label>
            </li>
        EOD;
    }

    echo <<<EOD
                </ul>
            </form>
        </div>
    EOD;

    global $scripts;
    $scripts["scripts/idSelector.js"] = 1;
}