<?php
/**
 * Application dependency
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Services\JwtService;
use function App\Helpers\env;

$privateKey = openssl_pkey_get_private(
    'file://'.env('PRIVATE_KEY_PATH'),
    env('PRIVATE_KEY_PASSPHRASE', '')
);

$publicKey = openssl_pkey_get_public(
    'file://'.env('PUBLIC_KEY_PATH')
);

if (false === $privateKey || false === $publicKey) {
    throw new Error('Cannot find keys to open.');
}

$jwtService = new JwtService($publicKey, $privateKey);

return [
    JwtService::class => $jwtService,
];
