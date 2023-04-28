<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Support\Facades\Cache;
use QuetzalStudio\SnapBi\Auth\AccessToken as AuthAccessToken;
use QuetzalStudio\SnapBi\Exceptions\AccessTokenException;

class AccessToken
{
    public function __construct(
        protected string $name,
    ) {}

    /**
     * Get cache name
     *
     * @return string
     */
    protected function cacheKey(): string
    {
        return "snap.{$this->name}.access_token";
    }

    /**
     * Store access token payload to cache
     *
     * @return void
     */
    protected function cache(array $payload): void
    {
        Cache::put($this->cacheKey(), $payload, data_get($payload, 'expiresIn', 600));
    }

    /**
     * Put access token payload to cache
     *
     * @return void
     */
    public static function put(string $name, array $payload): void
    {
        (new static($name))->cache($payload);
    }

    /**
     * Get access token instance
     *
     * @param string $name
     * @return static
     */
    public static function get(string $name): static
    {
        $token = new static($name);

        if (! Cache::has($token->cacheKey())) {
            (new AuthAccessToken())->get();
        }

        return $token;
    }

    public function __toString()
    {
        $payload = Cache::get($this->cacheKey());

        if (! $payload) {
            throw new AccessTokenException($this->name);
        }

        return data_get($payload, 'accessToken');
    }
}
