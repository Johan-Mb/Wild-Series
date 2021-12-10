<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class EpisodeFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $em, Slugify $slugify)
    {
        $this->season = $em->getRepository(Season::class);
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < count($this->season->findAll()); $i++)
        {
            $episode = new Episode();
            $title = "Lorem ipsum";
            $episode->setTitle($title);
            $episode->setSeasonId($this->season->find(44));
            $episode->setNumber($i);
            $episode->setSynopsis("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec eros ante. Nullam sed ipsum arcu. Curabitur ac porta lorem. Duis quis ex a risus tincidunt fermentum at nec justo. Nam aliquet molestie commodo. Sed dapibus pulvinar urna eget hendrerit. Vivamus viverra risus a pellentesque facilisis. Suspendisse ornare porta velit ut mollis.");
            $episode->setSlug($this->slugify->generate($title));
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

    public static function getGroups(): array
    {
        return ['EpisodesAndSeasons'];
    }

}