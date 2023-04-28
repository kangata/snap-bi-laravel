<?php

namespace QuetzalStudio\SnapBi\Exceptions;

use Exception;

class AccessTokenException extends Exception
{
    public function __construct(
        protected string $provider,
        ?string $message = null,
    )
    {
        $message = $message ?? 'Access token not defined or has expired';

        parent::__construct($message);
    }

    public function getProvider()
    {
        return $this->provider;
    }
}
