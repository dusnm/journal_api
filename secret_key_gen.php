<?php

function generate_secret_key_and_nonce(): array
{
    $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    return [
        'key' => $key,
        'nonce' => $nonce,
    ];
}

if (!file_exists(__DIR__.'/keys/secret_key') && !file_exists(__DIR__.'/keys/secret_key_nonce')) {
    $key_and_nonce = generate_secret_key_and_nonce();

    file_put_contents(__DIR__.'/keys/secret_key', $key_and_nonce['key']);
    file_put_contents(__DIR__.'/keys/secret_key_nonce', $key_and_nonce['nonce']);
}
