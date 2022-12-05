<?php

namespace App\Factory;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<CartItem>
 *
 * @method static CartItem|Proxy createOne(array $attributes = [])
 * @method static CartItem[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static CartItem[]|Proxy[] createSequence(array|callable $sequence)
 * @method static CartItem|Proxy find(object|array|mixed $criteria)
 * @method static CartItem|Proxy findOrCreate(array $attributes)
 * @method static CartItem|Proxy first(string $sortedField = 'id')
 * @method static CartItem|Proxy last(string $sortedField = 'id')
 * @method static CartItem|Proxy random(array $attributes = [])
 * @method static CartItem|Proxy randomOrCreate(array $attributes = [])
 * @method static CartItem[]|Proxy[] all()
 * @method static CartItem[]|Proxy[] findBy(array $attributes)
 * @method static CartItem[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static CartItem[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CartItemRepository|RepositoryProxy repository()
 * @method CartItem|Proxy create(array|callable $attributes = [])
 */
final class CartItemFactory extends ModelFactory
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
            'price' => self::faker()->randomNumber(),
            'qty' => self::faker()->randomNumber(),
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(CartItem $cartItem): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CartItem::class;
    }
}
