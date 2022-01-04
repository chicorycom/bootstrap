<?php


namespace Boot\Support;


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Redirect
{
    protected ResponseInterface $response;

    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->response = $factory->createResponse(302);
    }

    public function __invoke(string $to): ResponseInterface
    {
        $this->response = $this->response->withHeader('Location', $to);

        return $this->response;
    }
}