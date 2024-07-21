<?php

declare(strict_types=1);

namespace Tests;

use App\CommentClient;
use App\DTO\CommentDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class CommentClientTest extends TestCase
{
    /** @var CommentClient  */
    private CommentClient $client;
    /** @var MockHandler  */
    private MockHandler $mockHandler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);
        $this->client = new CommentClient($httpClient, new HttpFactory());
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     */
    public function testGetComments()
    {
        $comment = CommentDTO::fromArray(['id' => 1, 'name' => 'Dmitriy', 'text' => 'Test']);
        $response = new Response(200, [], json_encode([$comment]));
        $this->mockHandler->append($response);

        $result = $this->client->getComments();
        $this->assertObjectEquals($comment, $result[0]);
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     */
    public function testAddComment(): void
    {
        $comment = CommentDTO::fromArray(['id' => 2, 'name' => 'Alex', 'text' => 'Description']);
        $response = new Response(200, [], json_encode($comment));
        $this->mockHandler->append($response);
        $createdComment = $this->client->createComment($comment);

        $this->assertObjectEquals($comment, $createdComment);
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     */
    public function testUpdateComment(): void
    {
        $comment = CommentDTO::fromArray(['id' => 1, 'name' => 'New name', 'text' => 'New text']);
        $response = new Response(200, [], json_encode($comment));
        $this->mockHandler->append($response);
        $createdComment = $this->client->updateComment($comment);

        $this->assertObjectEquals($comment, $createdComment);
    }
}