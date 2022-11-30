<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Factory\SongFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SongsFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getSongs() as $song) {
            if (empty($song['name'])) {
                return;
            }

            $factory = new SongFactory();
            $factory->createOne([
                'name' => $song['name'],
                'author' => $song['author'] ?? '',
            ]);
        }

        $manager->flush();
    }

    /**
     * @return \string[][]
     */
    private function getSongs(): array
    {
        return [
            [
                'name' => 'Rich Flex',
            ],
            [
                'name' => 'Anti-Hero',
            ],
            [
                'name' => 'Unholy',
                'author' => 'Sam Smith & Kim Petras'
            ],
            [
                'name' => 'Bad Habit',
                'author' => 'Steve Lacy'
            ],
            [
                'name' => 'As It Was',
                'author' => 'Harry Styles'
            ],
            [
                'name' => 'Major Distribution',
                'author' => 'Drake & 21 Savage'
            ],
            [
                'name' => 'Lift Me Up',
                'author' => 'Rihanna'
            ]
        ];
    }
}
