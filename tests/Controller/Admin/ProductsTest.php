<?php

namespace App\Tests\Controller\Admin;

use App\Tests\Controller\BaseWebTestCase;
use App\Factory\ProductFactory;

class ProductsTest extends BaseWebTestCase
{
    public function test_index_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/products');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/products');

        $this->assertResponseIsSuccessful();
    }

    public function test_update_page()
    {
        $product = ProductFactory::createOne();

        $client = static::createClient();

        $client->request('GET', '/admin/products/update/' . $product->getSlug());

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/products/update/' . $product->getSlug());

        $this->assertResponseIsSuccessful();
    }

    public function test_create_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/products/create');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/products/create');

        $this->assertResponseIsSuccessful();
    }

    public function test_createPosition_page()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/products/create_position');

        $this->assertResponseRedirects();

        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/products/create_position');

        $this->assertResponseIsSuccessful();
    }
}