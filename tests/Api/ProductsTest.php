<?php

namespace App\Tests\Api;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\LoginManager;

class ProductsTest extends ApiTestCase
{
    use LoginManager;

    public function test_createProduct()
    {

        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => uniqid()
            ]
        ]);

        $this->assertResponseRedirects();


        $this->createLoggedInAdminUser($client);

        $client->request('POST', '/api/products', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json'
            ],
            'json' => [
                'name' => uniqid()
            ]
        ]);

        $this->assertResponseIsSuccessful();




    }
}