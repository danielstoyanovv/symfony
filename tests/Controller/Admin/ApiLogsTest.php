<?php

namespace App\Tests\Controller\Admin;

use App\Factory\ApiLogFactory;
use App\Tests\Controller\BaseWebTestCase;

class ApiLogsTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/api_logs');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/api_logs');

        $this->assertResponseIsSuccessful();
    }

    public function test_details_page()
    {
        $client = static::createClient();

        $apiLog = ApiLogFactory::createOne();

        $client->request('GET', '/admin/api_logs/' . $apiLog->getId() . '/details');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/api_logs/' . $apiLog->getId() . '/details');

        $this->assertResponseIsSuccessful();
    }
}