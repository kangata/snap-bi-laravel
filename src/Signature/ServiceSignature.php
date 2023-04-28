<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi\Signature;

class ServiceSignature
{
    /**
     * Generate symmetric signature for request a service
     *
     * @param string $clientSecret
     * @param ServiceSignatureData $data
     *
     * @return string
     */
    public static function symmetric(string $clientSecret, ServiceSignaturePayload $data): string
    {
        return base64_encode(hash_hmac('sha512', (string) $data, $clientSecret, true));
    }

    public static function symmetricVerify(
        ServiceSignaturePayload $data,
        string $signature,
        string $clientSecret
    ): bool {
        return hash_equals($signature, static::symmetric($clientSecret, $data));
    }
}
