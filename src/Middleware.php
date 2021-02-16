<?php

namespace Vssl\Render;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * Use this PSR-15 middleware to pre-render a particular page.
     *
     * @param  Psr\Http\Message\ServerRequestInterface $request PSR-7 request
     * @param  Psr\Http\Server\RequestHandlerInterface $handler PSR-15 request handler
     * @return void
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resolver = new Resolver($request);
        $request = $resolver->getRequest();
        return $handler->handle($request->withAttribute('vssl-resolver', $resolver));
    }
}
