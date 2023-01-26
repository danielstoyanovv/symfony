<?php

namespace App\Factory;

use App\Entity\Song;
use App\Repository\SongRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Song>
 *
 * @method static Song|Proxy createOne(array $attributes = [])
 * @method static Song[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Song[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Song|Proxy find(object|array|mixed $criteria)
 * @method static Song|Proxy findOrCreate(array $attributes)
 * @method static Song|Proxy first(string $sortedField = 'id')
 * @method static Song|Proxy last(string $sortedField = 'id')
 * @method static Song|Proxy random(array $attributes = [])
 * @method static Song|Proxy randomOrCreate(array $attributes = [])
 * @method static Song[]|Proxy[] all()
 * @method static Song[]|Proxy[] findBy(array $attributes)
 * @method static Song[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Song[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static SongRepository|RepositoryProxy repository()
 * @method Song|Proxy create(array|callable $attributes = [])
 */
final class SongFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(),
            'createdAt' => new \DateTime('now'),
            'updatedAt' => new \DateTime('now')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function (Song $song): void {
             })
        ;
    }

    protected static function getClass(): string
    {
        return Song::class;
    }
}
