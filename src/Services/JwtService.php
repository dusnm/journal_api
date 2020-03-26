<?php

namespace App\Services;

use function App\Helpers\env;
use Firebase\JWT\JWT;

class JwtService
{
    private $publicKey;

    private $privateKey;

    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function __destruct()
    {
        if (is_resource($this->privateKey)) {
            openssl_pkey_free($this->privateKey);
        }

        if (is_resource($this->publicKey)) {
            openssl_pkey_free($this->publicKey);
        }
    }

    public function sign(array $payload, int $exp): string
    {
        return JWT::encode(
            array_merge(
                [
                    'iss' => env('APP_URL'),
                    'iat' => time(),
                    'exp' => time() + $exp,
                ],
                $payload
            ),
            $this->privateKey,
            'RS512'
        );
    }

    public function decode(string $jwt)
    {
        return JWT::decode($jwt, $this->publicKey, ['RS512']);
    }
}
