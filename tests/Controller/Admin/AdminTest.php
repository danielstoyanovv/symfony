<?php

namespace App\Tests\Controller\Admin;

use App\Tests\Controller\BaseWebTestCase;

class AdminTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }
}