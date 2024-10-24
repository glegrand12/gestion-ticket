<?php

namespace App\DataFixtures;

use App\Entity\Tickets;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        /* user */
        $user = new Users();
        $user->setEmail('admin@admin.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, $user->getEmail()));
        $this->addReference($user->getEmail(), $user);
        $manager->persist($user);

        $user = new Users();
        $user->setEmail('visitor@visitor.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $user->getEmail()));
        $this->addReference($user->getEmail(), $user);
        $manager->persist($user);
        $manager->flush();

    }
}
