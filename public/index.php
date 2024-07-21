<?php

use App\CommentClient;
use App\DTO\CommentDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

require_once '../vendor/autoload.php';

$client = new CommentClient(new Client(['base_uri' => 'http://test.loc.de']), new HttpFactory());

$createComment = CommentDTO::fromArray(['name' => 'Andrey', 'text' => 'Hello World!']);
$updateComment = CommentDTO::fromArray(['id' => 1, 'name' => 'John', 'text' => 'Bye']);

$client->getComments();
$client->createComment($createComment);
$client->updateComment($updateComment);