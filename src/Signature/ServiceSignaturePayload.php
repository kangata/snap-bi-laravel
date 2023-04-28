<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi\Signature;

use Illuminate\Support\Facades\Log;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\Timestamp;

class ServiceSignaturePayload
{
    public function __construct(
        protected string $httpMethod,
        protected string $endpointUrl,
        protected string $accessToken,
        protected string $timestamp,
        protected array|string $payload = ''
    ) {}

    public function __toString()
    {
        $json = is_array($this->payload)
            ? json_encode($this->payload)
            : (string) $this->payload;

        $minified = hash('sha256', $json);

        $values = [
            $this->httpMethod,
            $this->endpointUrl,
            $this->accessToken,
            $minified,
            (string) new Timestamp($this->timestamp),
        ];

        $stringToSign = implode(':', $values);

        if (Config::instance()->isDebug()) {
            Log::debug(__CLASS__, ['json' => $json]);
            Log::debug(__CLASS__, ['string_to_sign' => $stringToSign]);
        }

        return $stringToSign;
    }
}
