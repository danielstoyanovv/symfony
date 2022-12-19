<?php

namespace App\Tests\Controller;

class CartTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/cart');

        $this->assertResponseIsSuccessful();
    }
}