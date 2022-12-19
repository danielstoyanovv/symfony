<?php

namespace App\Factory;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Order>
 *
 * @method static Order|Proxy createOne(array $attributes = [])
 * @method static Order[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Order[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Order|Proxy find(object|array|mixed $criteria)
 * @method static Order|Proxy findOrCreate(array $attributes)
 * @method static Order|Proxy first(string $sortedField = 'id')
 * @method static Order|Proxy last(string $sortedField = 'id')
 * @method static Order|Proxy random(array $attributes = [])
 * @method static Order|Proxy randomOrCreate(array $attributes = [])
 * @method static Order[]|Proxy[] all()
 * @method static Order[]|Proxy[] findBy(array $attributes)
 * @method static Order[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Order[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderRepository|RepositoryProxy repository()
 * @method Order|Proxy create(array|callable $attributes = [])
 */
final class OrderFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'total' => self::faker()->randomFloat(),
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Order $order): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Order::class;
    }
}
