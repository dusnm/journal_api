<?php

namespace App\Helpers;

/**
 * Return the value of an environment variable
 *
 * @param string $value The environment variable to be fetched
 * @param mixed $default The default value to be returned if the environment variable doesn't exist
 *
 * @return string|null An environment variable or null if no variable is found and the default value is not set
*/
function env(string $value, $default = null)
{
    return getenv($value) ?? $default;
}

/**
 * Prepend the base URL of the server to a path string
 *
 * @param string $path A path string to be appended to the base URL of the server
 *
 * @return string
*/
function url(string $path = ''): string
{
    return env('APP_URL', 'http://localhost:8000').$path;
}

/**
 * Generate a base64 url encoded random string
 *
 * @param int $length The length of the string to be generated
 *
 * @return string The generated random string base64 url encoded
*/
function randomString(int $length = 10): string
{
    $string = '';

    while (($len = strlen($string)) < $length) {
        $size = $length - $len;
        $bytes = random_bytes($size);
        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }

    return $string;
}
