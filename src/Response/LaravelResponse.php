<?php

namespace ByJG\ApiTools\Laravel\Response;

use ByJG\ApiTools\Response\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class LaravelResponse implements ResponseInterface
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getHeaders()
    {
        return $this->response->headers->all();
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getBody()
    {
        return $this->response->getContent();
    }
}
