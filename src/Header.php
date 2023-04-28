<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Contracts\Support\Arrayable;

class Header implements Arrayable
{
    public function __construct(
        protected ?string $contentType = 'application/json',
        protected ?string $clientKey = null,
        protected ?string $authorization = null,
        protected ?string $authorizationCustomer = null,
        protected ?string $timestamp = null,
        protected ?string $signature = null,
        protected ?string $origin = null,
        protected ?string $partnerId = null,
        protected ?string $externalId =  null,
        protected ?string $ipAddress = null,
        protected ?string $deviceId = null,
        protected ?float $latitude = null,
        protected ?float $longitude = null,
        protected string|int|null $channelId = null,
    ) {}

    public function toArray()
    {
        $data = [
            'Content-Type' => $this->contentType,
            'Authorization' => $this->authorization,
            'Authorization-Customer' => $this->authorizationCustomer,
            'X-CLIENT-KEY' => $this->clientKey,
            'X-TIMESTAMP' => $this->timestamp,
            'X-SIGNATURE' => $this->signature,
            'X-ORIGIN' => $this->origin,
            'X-PARTNER-ID' => $this->partnerId,
            'X-EXTERNAL-ID' => $this->externalId,
            'X-IP-ADDRESS' => $this->ipAddress,
            'X-DEVICE-ID' => $this->deviceId,
            'X-LATITUDE' => $this->latitude,
            'X-LONGITUDE' => $this->longitude,
            'CHANNEL-ID' => $this->channelId,
        ];

        foreach ($data as $key => $val) {
            if (is_null($val)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function only(array $keys): array
    {
        $attributes = [];

        $keys = array_map(fn ($val) => strtolower($val), $keys);

        foreach ($this->toArray() as $key => $val) {
            if (in_array(strtolower($key), $keys)) {
                $attributes[$key] = $val;
            }
        }

        return $attributes;
    }

    public function forGetAccessToken(): array
    {
        return $this->only([
            'content-type',
            'x-client-key',
            'x-timestamp',
            'x-signature',
        ]);
    }
}
