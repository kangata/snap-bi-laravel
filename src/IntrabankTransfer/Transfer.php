<?php

namespace QuetzalStudio\SnapBi\IntrabankTransfer;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use QuetzalStudio\SnapBi\AccessToken;
use QuetzalStudio\SnapBi\Client;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\Contracts\ServicePayload;
use QuetzalStudio\SnapBi\HeaderFactory;
use QuetzalStudio\SnapBi\Signature\ServiceSignature;
use QuetzalStudio\SnapBi\Signature\ServiceSignaturePayload;

class Transfer
{
    protected ?Config $config = null;

    protected Client $client;

    protected ?AccessToken $accessToken = null;

    public string $endpoint = '/v1.0/transfer-intrabank';

    public function __construct(
        protected string $origin,
        protected string|int $channelId,
        protected string|int $externalId,
        protected ?ServicePayload $payload = null,
        protected string|int|null $timestamp = null,
    ) {
        $this->config = Config::instance();
        $this->client = new Client();
        $this->accessToken = AccessToken::get($this->config?->provider()->name());
        $this->timestamp = $timestamp ?? time();
    }

    /**
     * Send transfer request
     *
     * @param Payload|null $payload
     * @return Response
     *
     * @throws ConnectionException|RequestException
     */
    public function send(?ServicePayload $payload = null): Response
    {
        if ($payload) {
            $this->payload = $payload;
        }

        return $this->client
            ->withHeaders($this->headers())
            ->post($this->config->provider()->serviceUrl($this->endpoint), $this->payload->toArray());
    }

    /**
     * Generate transfer signature
     */
    private function signature(): string
    {
        return ServiceSignature::symmetric(
            $this->config->provider()->clientSecret(),
            new ServiceSignaturePayload(
                'POST',
                $this->config->provider()->relativePath($this->endpoint),
                (string) $this->accessToken,
                $this->timestamp,
                $this->payload->toArray(),
            ),
        );
    }

    /**
     * Generate request headers
     */
    private function headers(): array
    {
        return HeaderFactory::make([
            'authorization' => 'Bearer ' . (string) $this->accessToken,
            'timestamp' => $this->timestamp,
            'signature' => $this->signature(),
            'partner_id' => $this->config->provider()->partnerId(),
            'origin' => $this->origin,
            'external_id' => (string) $this->externalId,
            'channel_id' => (string) $this->channelId,
        ])->toArray();
    }
}
