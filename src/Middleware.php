<?php

namespace Vessel\Render;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * Use this PSR-15 middleware to pre-render a particular page.
     *
     * @param  \Interop\Http\ServerMiddleware\MiddlewareInterface $request HTTP Request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface   $next    Next delegate
     * @return void
     */
    public function process(ServerRequestInterface $request, DelegateInterface $next)
    {
        $resolver = new Resolver($request);
        return $next->process($resolver->getRequest());
    }
}
