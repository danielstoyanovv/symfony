<?php

namespace App\Tests\Controller;

class SongsTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/songs');

        $this->assertResponseIsSuccessful();
    }
}