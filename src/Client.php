<?php

declare(strict_types = 1);

namespace QuetzalStudio\SnapBi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    protected Config $config;

    protected ?PendingRequest $pendingRequest = null;

    protected bool $throwError;

    public function __construct(array $options = [])
    {
        $this->throwError = data_get($options, 'throw', true);
    }

    public function __call($method, $arguments)
    {
        if (! $this->pendingRequest) {
            $this->pendingRequest = Http::acceptJson();
        }

        if ($method == 'withHeaders') {
            $this->pendingRequest->withHeaders(...$arguments);

            return $this;
        }

        if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            $response = $this->pendingRequest->$method(...$arguments);

            RequestLogger::dispatch($response);

            $this->pendingRequest = null;

            if ($this->throwError) {
                $response = $response->throw();
            }

            return $response;
        }

        $this->pendingRequest->$method(...$arguments);

        return $this;
    }
}
