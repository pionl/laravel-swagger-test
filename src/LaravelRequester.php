<?php

namespace ByJG\ApiTools\Laravel;

use ByJG\ApiTools\AbstractRequester;
use ByJG\ApiTools\Laravel\Response\LaravelResponse;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Str;

class LaravelRequester extends AbstractRequester
{
    /**
     * @var TestCase
     */
    protected $testCase;

    public function __construct(TestCase $testCase)
    {
        parent::__construct();
        $this->testCase = $testCase;
    }

    protected function handleRequest($path, $headers)
    {
        $testResponse = $this->testCase->call(
            $this->method,
            $path,
            [],
            [],
            [],
            // Convert headers to server headers
            collect($headers)->mapWithKeys(function ($value, $name) {
                $name = strtr(strtoupper($name), '-', '_');

                return [$this->formatServerHeaderKey($name) => $value];
            })->all(),
            json_encode($this->requestBody)
        );
        return new LaravelResponse($testResponse->baseResponse);
    }


    /**
     * Format the header name for the server array.
     *
     * @param string $name
     *
     * @return string
     */
    protected function formatServerHeaderKey($name)
    {
        if (!Str::startsWith($name, 'HTTP_') && $name !== 'CONTENT_TYPE' && $name !== 'REMOTE_ADDR') {
            return 'HTTP_'.$name;
        }

        return $name;
    }
}
