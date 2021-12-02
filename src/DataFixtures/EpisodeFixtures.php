<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 16; $i++) {
            $episode = new Episode();
            $episode->setSeasonId(2);
            $manager->persist($episode);
        }
        $manager->flush();
    }

}