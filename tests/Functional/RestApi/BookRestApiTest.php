<?php

declare(strict_types=1);

namespace Bookshop\Tests\Functional\RestApi;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookRestApiTest extends WebTestCase
{
    public function testCreateBook()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/books',
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            [],
            [],
            json_encode([
                'isbn' => '9781234567897',
                'title' => 'Clean Code 3',
                'author_id' => 'e7348bc0-b021-47f5-b61c-cd27a13f9519',
                'price' => 49.99,
            ])
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
