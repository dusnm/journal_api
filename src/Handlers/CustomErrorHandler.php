<?php

namespace App\Handlers;

use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

class CustomErrorHandler extends ErrorHandler
{
    private Logger $log;

    public function __construct(CallableResolverInterface $callabbleResolver, ResponseFactoryInterface $responseFactory, Logger $log)
    {
        parent::__construct($callabbleResolver, $responseFactory);

        $this->log = $log;
    }

    public function logError(string $error): void
    {
        $this->log->error($error);
    }
}
