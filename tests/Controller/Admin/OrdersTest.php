<?php

namespace App\Tests\Controller\Admin;

use App\Factory\OrderFactory;
use App\Tests\Controller\BaseWebTestCase;

class OrdersTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/orders');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/orders');

        $this->assertResponseIsSuccessful();
    }

    public function test_details_page()
    {
        $client = static::createClient();

        $order = OrderFactory::createOne();

        $client->request('GET', '/admin/orders/' . $order->getId() . '/details');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/orders/' . $order->getId() . '/details');

        $this->assertResponseIsSuccessful();
    }
}