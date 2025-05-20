<?php

function showModal($header, $body, $footer)
{
    echo <<<EOD
        <div class="modal d-block" tabindex="-1" aria-labelledby="modalMessage" data-bs-backdrop="static" aria-hidden="true" id="modalMessage" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
    EOD;

    if (isset($header)) {
        echo <<<EOD
            <div class="modal-header bg-body-tertiary">
                $header
            </div>
        EOD;
    }

    if (isset($body)) {
        echo <<<EOD
            <div class="modal-body">
                $body
            </div>
        EOD;
    }

    if (isset($footer)) {
        echo <<<EOD
            <div class="modal-footer bg-body-tertiary">
                $footer
            </div>
        EOD;
    }

    echo <<<EOD
                </div>
            </div>
        </div>
    EOD;
}