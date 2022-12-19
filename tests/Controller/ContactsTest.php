<?php

namespace App\Tests\Controller;

class ContactsTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/contacts');

        $this->assertResponseIsSuccessful();
    }
}