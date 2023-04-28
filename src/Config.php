<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

class Config
{
    protected ?PrivateKey $privateKey = null;

    protected ?Provider $provider = null;

    protected static ?Config $instance = null;

    protected static bool $debug = false;

    public function __construct(
        ?string $name = null
    ) {
        $this->privateKey = new PrivateKey($name);
        $this->provider = new Provider($name);
    }

    public function privateKey(): ?PrivateKey
    {
        return $this->privateKey;
    }

    public function provider(): ?Provider
    {
        return $this->provider;
    }

    public function logChannel(): ?string
    {
        return $this->provider->logChannel();
    }

    public function isDebug(): bool
    {
        return static::$debug;
    }

    public static function instance(): ?static
    {
        return static::$instance;
    }

    public static function load(string $name): ?static
    {
        return static::$instance = new static($name);
    }

    public static function debug(bool $isDebug): void
    {
        static::$debug = $isDebug;
    }
}
