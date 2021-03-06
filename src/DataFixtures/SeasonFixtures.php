<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class SeasonFixtures extends Fixture implements FixtureGroupInterface
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->program = $em->getRepository(Program::class);
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < count($this->program->findAll()); $i++)
        {
            $season = new Season();
            $season->setYear(mt_rand(2000, 2021));
            $season->setProgramId($this->program->find(36));
            $season->setNumber($i);
            $season->setDescription("Season ". $i);
            $manager->persist($season);
            $this->addReference('saison_' . $i, $season);
        }
           $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          EpisodeFixtures::class,
          ProgramFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['EpisodesAndSeasons'];
    }

}
