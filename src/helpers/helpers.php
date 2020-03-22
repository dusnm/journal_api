<?php

namespace App\Helpers;

function env(string $value, $default = null)
{
    return getenv($value) ?? $default;
}
