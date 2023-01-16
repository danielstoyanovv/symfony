<?php

namespace App\Tests\Api;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class BaseApiTestCase extends ApiTestCase
{
    public function createProduct(Client $client)
    {
        $client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => uniqid()
            ]
        ]);
    }
}