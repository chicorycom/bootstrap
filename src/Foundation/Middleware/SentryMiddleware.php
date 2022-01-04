<?php

namespace Boot\Foundation\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Throwable;

/**
 * Middleware Must Also Be Registered To HttpKernel or Registered on specific routes
 **/
class SentryMiddleware
{

    public function __invoke(Request $request, Handle $handler)
    {
        try {
            \Sentry\init(config('sentry'));

            return $handler->handle($request);
        } catch (Throwable $exception) {
            \Sentry\captureException($exception);

            throw $exception;
        }
    }

}
