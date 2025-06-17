<?php

function showModal($header, $body, $footer, $actionUrl, $method, $id)
{
    echo <<<EOD
        <div class="modal fade mh-100 max-vh-100" tabindex="-1" id="$id">
            <form action="$actionUrl" method="$method" class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        $header
                    </div>
                    <div class="modal-body d-flex flex-column gap-3">
    EOD;

    if (function_exists($body)) {
        $body();
    } else {
        echo $body;
    }

    echo <<<EOD
                    </div>
                    <div class="modal-footer">
                        $footer
                    </div>
                </div>
            </form>
        </div>
    EOD;
}