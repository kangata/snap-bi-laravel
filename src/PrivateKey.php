<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class PrivateKey
{
    /**
     * The private key name
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The private key path
     *
     * @var string|null
     */
    protected ?string $path = null;

    public function __construct(?string $name = null)
    {
        if ($name) {
            $this->name = strtolower($name);
            $this->path = Config::get("snap.providers.{$this->name}.private_key") ?? Config::get("snap.private_key");

            if (! preg_match('/^\//', $this->path)) {
                $this->path = App::storagePath($this->path);
            }
        }
    }

    /**
     * Get path of private key
     *
     * @return string|null
     */
    public function path(): ?string
    {
        return $this->path;
    }

    public function __toString()
    {
        return file_get_contents($this->path);
    }
}
