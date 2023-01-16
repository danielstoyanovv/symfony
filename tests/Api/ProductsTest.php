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

        $this->assertResponseRedirects();

        $this->createLoggedInAdminUser($client);

        $this->createProduct($client);

        $this->assertResponseIsSuccessful();
    }
}