<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi\Signature;

use Illuminate\Support\Facades\Log;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\Timestamp;

class AccessTokenSignaturePayload
{
    public function __construct(
        protected string $clientKey,
        protected string|int $timestamp,
    ) {}

    public function __toString()
    {
        $timestamp = (string) new Timestamp($this->timestamp);

        $stringToSign = "{$this->clientKey}|{$timestamp}";

        if (Config::instance()->isDebug()) {
            Log::debug(__CLASS__, ['string_to_sign' => $stringToSign]);
        }

        return $stringToSign;
    }
}
