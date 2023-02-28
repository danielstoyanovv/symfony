<?php

namespace App\Tests\Api;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class BaseApiTestCase extends ApiTestCase
{
    public function createProduct(Client $client, $token = null)
    {
        $client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
                'X-AUTH-TOKEN' => $token
            ],
            'json' => [
                'name' => uniqid()
            ]
        ]);
    }

    public function createApiToken(string $email, Client $client)
    {
        $client->request('POST', '/api/api_tokens', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'email' => $email,
                'password' => '123456'
            ]
        ]);
    }

}