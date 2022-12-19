<?php

namespace App\Tests\Controller;

class ProductsTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
    }
}