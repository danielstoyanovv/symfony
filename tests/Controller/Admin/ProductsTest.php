<?php

namespace App\Tests\Controller\Admin;

use App\Repository\ProductRepository;
use App\Tests\Controller\BaseWebTestCase;
use App\Factory\ProductFactory;
use App\Entity\Product;

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

    public function test_all_product_methods()
    {
        $product = ProductFactory::createOne([
            'name' => 'Test all product methods',
            'description' => 'Description',
            'price' => 111,
            'position' => 1,
            'status' => 1
        ]);
        $this->assertEquals(Product::class, get_class($product->object()));
        $this->assertEquals('Test all product methods', $product->getName());
        $this->assertEquals('Description', $product->getDescription());
        $this->assertEquals(111, $product->getPrice());
        $this->assertEquals(1, $product->getStatus());
        $this->assertEquals('test-all-product-methods', $product->getSlug());
        $productFromRepo = static::getContainer()->get(ProductRepository::class)->findOneBy(['name' => $product->getName()]);
        $this->assertEquals(Product::class, get_class($productFromRepo));
        $this->assertEquals($product->getId(), $productFromRepo->getId());

        $product->remove();
        $this->assertEquals(0,$product->getId());
    }
}