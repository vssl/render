<?php

namespace Tests\Mocks;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

class MockDelegate implements DelegateInterface
{
    /**
     * The response handled by the process method.
     *
     * @var void
     */
    protected $request;
    
    /**
     * Process the next middleware.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return void
     */
    public function process(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Return the request object.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
