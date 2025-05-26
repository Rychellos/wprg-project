<?php

/**
 * Prints input with specified values
 * @param string $name ID and name property on html elements
 * @param string $type ID and name property on html elements
 * @param string $label Text of label
 * @param string $iconClassName Bootstrap Icons' classname
 * @param string $pattern Regex pattern
 * @param string $errorMessage
 * @param string $value
 * @return void
 */
function baseInput(
    $name,
    $type,
    $label,
    $iconClassName,
    $pattern,
    $errorMessage,
    $value,
    $inputClassName = "",
    $required = false,
    $autocomplete = false,
) {
    echo <<<EOD
        <div class="input-group has-validation shadow">
            <span class="input-group-text rounded-end-0">
                <i class="bi $iconClassName fs-4 cs-fw"></i>
            </span>
            <div class="form-floating">
                <input
                    required="$required"
                    autocomplete="$autocomplete"
                    type="$type"
                    pattern="$pattern"
                    class="form-control $inputClassName"
                    name="$name"
                    id="$name"
                    placeholder="$label"
                    value="$value"
                />
                <label for="$name">$label</label>
                <div class="invalid-tooltip">
                    $errorMessage
                </div>
            </div>
        </div>
    EOD;
}