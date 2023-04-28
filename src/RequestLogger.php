<?php

namespace QuetzalStudio\SnapBi;

use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use QuetzalStudio\SnapBi\Config;

class RequestLogger
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public static function dispatch(Response $response)
    {
        return new static($response);
    }

    /**
     * Get logger instance
     *
     * @return Logger|null
     */
    public function log()
    {
        if (is_null(Config::instance()->logChannel())) {
            return null;
        }

        return Log::channel(Config::instance()->logChannel());
    }

    /**
     * Get log message
     *
     * @param Request $request
     * @return string
     */
    public function message(Request $request)
    {
        return implode(' ', [
            (string) $request->getMethod(),
            (string) $request->getUri(),
            $this->response->status(),
        ]);
    }

    /**
     * Get request context
     *
     * @param Request $request
     * @return array
     */
    public function requestContext(Request $request)
    {
        $reqBody = json_decode($request->getBody(), true);
        $reqHeaders = $request->getHeaders();

        return [
            'body' => $this->body($reqBody),
            'headers' => $this->headers($reqHeaders),
        ];
    }

    /**
     * Get response context
     *
     * @return array
     */
    public function responseContext()
    {
        return [
            'body' => $this->body($this->response->json()),
            'headers' => $this->headers($this->response->headers()),
        ];
    }

    private function headers(array $headers): array
    {
        if (Config::instance()->isDebug()) {
            return $headers;
        }

        $hidden = [
            'authorization',
            'x-signature',
        ];

        return collect($headers)->map(function ($val, $key) use ($hidden) {
            if (in_array(strtolower($key), $hidden) && is_array($val) && ! empty($val)) {
                return ['**********'];
            }

            return $val;
        })->toArray();
    }

    private function body(array $body): array
    {
        if (Config::instance()->isDebug()) {
            return $body;
        }

        $hidden = [
            'accessToken',
        ];

        return collect($body)->map(function ($val, $key) use ($hidden) {
            if (in_array($key, $hidden)) {
                return '**********';
            }

            return $val;
        })->toArray();
    }

    public function __destruct()
    {
        if (! $this->log()) {
            return;
        }

        try {
            $request = $this->response->transferStats->getRequest();

            $this->log()->log(Config::instance()->isDebug() ? 'DEBUG' : 'INFO', $this->message($request), [
                'request' => $this->requestContext($request),
                'response' => $this->responseContext(),
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
