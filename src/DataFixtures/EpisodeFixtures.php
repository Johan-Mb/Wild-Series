<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class EpisodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $season = $this->getDoctrine()
        //      ->getRepository(Season::class)
        //      ->find(2);

        // for ($i = 1; $i <= 16; $i++) {
        //     $episode = new Episode();
        //     $episode->setSeasonId($season);
        //     $manager->persist($episode);
        // }
        // $manager->flush();
    }

}