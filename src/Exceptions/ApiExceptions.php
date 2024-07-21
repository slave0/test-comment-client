<?php

declare(strict_types=1);

namespace App\Exceptions;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class ApiExceptions extends \RuntimeException implements ClientExceptionInterface
{
    public function __construct(string $message, private readonly ResponseInterface $response)
    {
        parent::__construct($message);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}