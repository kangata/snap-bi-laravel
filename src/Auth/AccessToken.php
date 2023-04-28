<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi\Auth;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use QuetzalStudio\SnapBi\AccessToken as Token;
use QuetzalStudio\SnapBi\Client;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\HeaderFactory;
use QuetzalStudio\SnapBi\Signature\AccessTokenSignature;
use QuetzalStudio\SnapBi\Signature\AccessTokenSignaturePayload;

class AccessToken
{
    protected ?Config $config = null;

    protected Client $client;

    public string $endpoint = '/v1.0/access-token/b2b';

    public function __construct(
        protected string|int|null $timestamp = null,
        protected bool $cache = true,
    ) {
        $this->config = Config::instance();
        $this->client = new Client();
        $this->timestamp = $timestamp ?? time();
    }

    /**
     * Get access token to provider
     * @return Response
     *
     * @throws ConnectionException|RequestException
     */
    public function get(): Response
    {
        $resp = $this->client
            ->withHeaders($this->headers())
            ->post($this->config->provider()->serviceUrl($this->endpoint), [
                'grantType' => 'client_credentials',
            ]);

        if ($resp->ok() && $this->cache) {
            Token::put($this->config->provider()->name(), $resp->json());
        }

        return $resp;
    }

    /**
     * Generate access token signature
     */
    private function signature(): string
    {
        return AccessTokenSignature::asymmetric(
            (string) $this->config->privateKey(),
            new AccessTokenSignaturePayload(
                $this->config->provider()->clientKey(),
                $this->timestamp
            ),
        );
    }

    /**
     * Generate request headers
     */
    private function headers(): array
    {
        return HeaderFactory::make([
            'client_key' => $this->config->provider()->clientKey(),
            'timestamp' => $this->timestamp,
            'signature' => $this->signature(),
        ])->forGetAccessToken();
    }
}
