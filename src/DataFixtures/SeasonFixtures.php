<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();


        $season = new Season();
        $season->setProgramId(13);
        $season->setNumber(1);
        $season->setYear(2008);
        $season->setDescription("Season 1");

        $manager->persist($season);
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }
}
