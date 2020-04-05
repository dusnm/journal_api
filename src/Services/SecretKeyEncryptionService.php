<?php

namespace App\Services;

class SecretKeyEncryptionService
{
    private $secretKey;
    private $nonce;

    public function __construct($secretKey, $nonce)
    {
        $this->secretKey = $secretKey;
        $this->nonce = $nonce;
    }

    public function secretKeyEncrypt(string $plainText): string
    {
        return sodium_crypto_secretbox($plainText, $this->nonce, $this->secretKey);
    }

    public function secretKeyDecrypt($cipherText): string
    {
        return sodium_crypto_secretbox_open($cipherText, $this->nonce, $this->secretKey);
    }
}
