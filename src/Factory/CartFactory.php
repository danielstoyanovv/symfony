<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Cart>
 *
 * @method static Cart|Proxy createOne(array $attributes = [])
 * @method static Cart[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Cart[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Cart|Proxy find(object|array|mixed $criteria)
 * @method static Cart|Proxy findOrCreate(array $attributes)
 * @method static Cart|Proxy first(string $sortedField = 'id')
 * @method static Cart|Proxy last(string $sortedField = 'id')
 * @method static Cart|Proxy random(array $attributes = [])
 * @method static Cart|Proxy randomOrCreate(array $attributes = [])
 * @method static Cart[]|Proxy[] all()
 * @method static Cart[]|Proxy[] findBy(array $attributes)
 * @method static Cart[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Cart[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CartRepository|RepositoryProxy repository()
 * @method Cart|Proxy create(array|callable $attributes = [])
 */
final class CartFactory extends ModelFactory
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
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Cart $cart): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Cart::class;
    }
}
