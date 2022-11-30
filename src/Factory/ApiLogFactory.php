<?php

namespace App\Factory;

use App\Entity\ApiLog;
use App\Repository\ApiLogRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<ApiLog>
 *
 * @method static ApiLog|Proxy createOne(array $attributes = [])
 * @method static ApiLog[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ApiLog[]|Proxy[] createSequence(array|callable $sequence)
 * @method static ApiLog|Proxy find(object|array|mixed $criteria)
 * @method static ApiLog|Proxy findOrCreate(array $attributes)
 * @method static ApiLog|Proxy first(string $sortedField = 'id')
 * @method static ApiLog|Proxy last(string $sortedField = 'id')
 * @method static ApiLog|Proxy random(array $attributes = [])
 * @method static ApiLog|Proxy randomOrCreate(array $attributes = [])
 * @method static ApiLog[]|Proxy[] all()
 * @method static ApiLog[]|Proxy[] findBy(array $attributes)
 * @method static ApiLog[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ApiLog[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ApiLogRepository|RepositoryProxy repository()
 * @method ApiLog|Proxy create(array|callable $attributes = [])
 */
final class ApiLogFactory extends ModelFactory
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
            'apiName' => self::faker()->text(),
            'responseData' => self::faker()->text(),
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(ApiLog $apiLog): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ApiLog::class;
    }
}
