<?php

namespace Boot\Support;


use Jenssegers\Blade\Blade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class View
{

    public Blade $blade;
    protected ResponseInterface $response;

    public function __construct(Blade $blade, ResponseFactoryInterface $factory)
    {
        $this->blade = $blade;
        $this->response = $factory->createResponse(200, 'Success');
    }

    public function __invoke(string $template = '', array $with = []) : ResponseInterface
    {
        $token = app()->resolve('csrf');
        $this->blade->share('token', [
            $token->getTokenNameKey() =>  $token->getTokenName(),
            $token->getTokenValueKey() => $token->getTokenValue()
        ]);

        $view = $this->blade->make($template, $with)->render();

        $this->response->getBody()->write($view);

        return $this->response;
    }
}