<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class UserFixtures extends Fixture implements FixtureGroupInterface
{

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail("bob@wilder.com");
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            "myadminpassword"
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $contributor = new User();
        $contributor->setEmail("michele@wilder.com");
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            "mycontributorpassword"
        );
        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['UserGroup'];
    }

}
