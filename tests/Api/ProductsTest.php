<?php

namespace App\Tests\Api;

use App\Tests\LoginManager;

class ProductsTest extends BaseApiTestCase
{
    use LoginManager;

    public function test_createProduct()
    {
        $client = static::createClient();

        $this->createProduct($client);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());

        $adminUser = $this->createLoggedInAdminUser($client);
        $this->createApiToken($adminUser->getEmail(), $client);
        $this->assertResponseIsSuccessful();
        $tokenData = json_decode($client->getResponse()->getContent(), true);

        if (!empty($tokenData['token'])) {
            $this->createProduct($client, $tokenData['token']);
             $this->assertResponseIsSuccessful();
        }

    }
}