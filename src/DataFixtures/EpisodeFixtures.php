<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;


class EpisodeFixtures extends Fixture
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->season = $em->getRepository(Season::class);
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < count($this->season->findAll()); $i++)
        {
            $episode = new Episode();
            $episode->setTitle("Lorem Ipsum super chouette");
            $episode->setSeasonId($this->season->find(25));
            $episode->setNumber($i);
            $episode->setSynopsis("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec eros ante. Nullam sed ipsum arcu. Curabitur ac porta lorem. Duis quis ex a risus tincidunt fermentum at nec justo. Nam aliquet molestie commodo. Sed dapibus pulvinar urna eget hendrerit. Vivamus viverra risus a pellentesque facilisis. Suspendisse ornare porta velit ut mollis.");
            $manager->persist($episode);
        }
           $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }

}