<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class ProgramFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function __construct(EntityManagerInterface $em, Slugify $slugify)
    {
        $this->user = $em->getRepository(User::class);
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $title = "Peaky Blinders";
        $program->setTitle($title);
        $program->setSummary("Fondée sur l'histoire du gang des Peaky Blinders, actif à la fin du xixe siècle, cette série suit un groupe de gangsters de Birmingham à partir de 1919. Cette bande, emmenée par l'ambitieux et dangereux Thomas Shelby et formée de sa fratrie, pratique le racket, la protection, la contrebande d'alcool et de tabac et les paris illégaux. Un vol d'armes automatiques dont ils sont les premiers soupçonnés pousse Winston Churchill à déléguer sur place l'inspecteur en chef Chester Campbell, officier de la police royale irlandaise qui emporte avec lui certaines méthodes expéditives…");
        $program->setPoster('https://cdn.shopify.com/s/files/1/0969/9128/products/PeakyBlinders-ThomasShelby-GarrisonBombing-NetflixTVShow-ArtPoster_b85366b9-72b6-4652-983e-2690676096da.jpg?v=1619864659');
        $program->setCategory($this->getReference('category_0'));
        $program->setSlug($this->slugify->generate($title));
        $program->setOwner($this->user->find(4));

        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
        for ($i=0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }

        $manager->persist($program);
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
          SeasonFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['ProgramGroup'];
    }
}