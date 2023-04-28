<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Support\Facades\Config;

class Provider
{
    /**
     * The rovider name
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The partner id
     */
    protected ?string $partnerId = null;

    /**
     * The provider client key
     *
     * @var string|null
     */
    protected ?string $clientKey = null;

    /**
     * The provider client secret
     *
     * @var string|null
     */
    protected ?string $clientSecret = null;

    /**
     * The provider base url
     *
     * @var string|null
     */
    protected ?string $baseUrl = null;

    /**
     * The provider api prefix
     *
     * @var string|null
     */
    protected ?string $apiPrefix = null;

    /**
     * Get log channel
     *
     * @var string|null
     */
    protected ?string $logChannel = null;

    public function __construct(?string $name = null)
    {
        if ($name) {
            $this->load($name);
        }
    }

    /**
     * Load provider config
     *
     * @param string $name
     * @return void
     */
    public function load(string $name): void
    {
        $this->name = strtolower($name);

        $config = Config::get("snap.providers.{$this->name}");

        $this->partnerId = data_get($config, 'partner_id');
        $this->clientKey = data_get($config, 'client_key');
        $this->clientSecret = data_get($config, 'client_secret');
        $this->baseUrl = data_get($config, 'host');
        $this->apiPrefix = data_get($config, 'api_prefix');
        $this->logChannel = data_get($config, 'log_channel');
    }

    /**
     * Get provider name
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * Get partner id
     *
     * @return string|null
     */
    public function partnerId(): ?string
    {
        return $this->partnerId;
    }

    /**
     * Get provider client key
     *
     * @return string|null
     */
    public function clientKey(): ?string
    {
        return $this->clientKey;
    }

    /**
     * Get provider client secret
     *
     * @return string|null
     */
    public function clientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * Get provider base url
     *
     * @return string|null
     */
    public function baseUrl(): ?string
    {
        return $this->baseUrl;
    }

    /**
     * Get relative path
     *
     * @param string $endpoint
     * @return string
     */
    public function relativePath(string $endpoint): string
    {
        return $this->apiPrefix.$endpoint;
    }

    /**
     * Get service url
     *
     * @param string $endpoint
     * @return string
     */
    public function serviceUrl(string $endpoint): string
    {
        return $this->baseUrl().$this->relativePath($endpoint);
    }

    /**
     * Get log channel
     *
     * @return string|null
     */
    public function logChannel(): ?string
    {
        return $this->logChannel;
    }
}
