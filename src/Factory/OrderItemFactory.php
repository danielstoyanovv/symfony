<?php

namespace App\Factory;

use App\Entity\OrderItem;
use App\Repository\OrderItemRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<OrderItem>
 *
 * @method static OrderItem|Proxy createOne(array $attributes = [])
 * @method static OrderItem[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static OrderItem[]|Proxy[] createSequence(array|callable $sequence)
 * @method static OrderItem|Proxy find(object|array|mixed $criteria)
 * @method static OrderItem|Proxy findOrCreate(array $attributes)
 * @method static OrderItem|Proxy first(string $sortedField = 'id')
 * @method static OrderItem|Proxy last(string $sortedField = 'id')
 * @method static OrderItem|Proxy random(array $attributes = [])
 * @method static OrderItem|Proxy randomOrCreate(array $attributes = [])
 * @method static OrderItem[]|Proxy[] all()
 * @method static OrderItem[]|Proxy[] findBy(array $attributes)
 * @method static OrderItem[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static OrderItem[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderItemRepository|RepositoryProxy repository()
 * @method OrderItem|Proxy create(array|callable $attributes = [])
 */
final class OrderItemFactory extends ModelFactory
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
            'price' => self::faker()->randomFloat(),
            'qty' => self::faker()->randomNumber(),
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(OrderItem $orderItem): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OrderItem::class;
    }
}
