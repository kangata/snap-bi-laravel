<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi\Signature;

use Exception;
use InvalidArgumentException;

class AccessTokenSignature
{
    /**
     * Generate asymmetric signature for request a access token
     *
     * @param string $clientKey
     * @param string $privateKey
     * @param string|int $timestamp
     *
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function asymmetric(string $privateKey, AccessTokenSignaturePayload $data): string
    {
        $asymmetricKey = openssl_pkey_get_private($privateKey);

        if (! $asymmetricKey) {
            throw new InvalidArgumentException('Invalid private key.');
        }

        $signature = null;

        openssl_sign((string) $data, $signature, $asymmetricKey, 'RSA-SHA256');

        if (! $signature) {
            throw new Exception('Signature failed to generate.');
        }

        return base64_encode($signature);
    }

    /**
     * Verify signature
     *
     * @param AccessTokenSignatureData $data
     * @param string $signature
     * @param string $publicKey
     */
    public static function asymmetricVerify(
        AccessTokenSignaturePayload $data,
        string $signature,
        string $publicKey
    ): int {
        $asymmetricKey = openssl_pkey_get_public($publicKey);

        return openssl_verify((string) $data, base64_decode($signature), $asymmetricKey, 'RSA-SHA256');
    }
}
