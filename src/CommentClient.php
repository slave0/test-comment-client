<?php

declare(strict_types=1);

namespace App;

use App\DTO\CommentDTO;
use App\Exceptions\ApiExceptions;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class CommentClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory
    ) {
    }

    /**
     * @return CommentDTO[]
     * @throws ClientExceptionInterface
     */
    public function getComments(): array
    {
        $request = $this->requestFactory
            ->createRequest('GET', 'comments')
            ->withHeader('Content-Type', 'application/json');
        $response = $this->httpClient->sendRequest($request);

        $this->checkStatusCode($response);
        $data = json_decode($response->getBody()->getContents(), true);

        return array_map(
            fn(array $item) => CommentDTO::fromArray($item),
            $data
        );
    }

    /**
     * @param CommentDTO $comment
     * @return CommentDTO
     * @throws ClientExceptionInterface
     */
    public function createComment(CommentDTO $comment): CommentDTO
    {
        $request = $this->requestFactory
            ->createRequest('POST', 'comment')
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->requestFactory->createStream(json_encode($comment)));

        $response = $this->httpClient->sendRequest($request);
        $data = json_decode($response->getBody()->getContents(), true);

        return CommentDTO::fromArray($data);
    }

    /**
     * @param CommentDTO $comment
     * @return CommentDTO
     * @throws ClientExceptionInterface
     */
    public function updateComment(CommentDTO $comment): CommentDTO
    {
        $request = $this->requestFactory
            ->createRequest('PUT', "comment/{$comment->id}")
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->requestFactory->createStream(json_encode($comment)));

        $response = $this->httpClient->sendRequest($request);
        $this->checkStatusCode($response);
        $data = json_decode($response->getBody()->getContents(), true);

        return CommentDTO::fromArray($data);
    }

    /**
     * @param ResponseInterface $response
     * @return void
     */
    private function checkStatusCode(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200 && $statusCode !== 201) {
            throw new ApiExceptions('Unexpected HTTP status code', $response);
        }
    }
}