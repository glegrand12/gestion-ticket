<?php

namespace App\DataFixtures;

use App\Entity\Tickets;
use App\Entity\Users;
use App\Enum\TicketPriorityType;
use App\Enum\TicketStatusType;
use App\Repository\UsersRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userRepository;


    public function __construct(private readonly UserPasswordHasherInterface $hasher, UsersRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $count = 20;

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


        for ($i = 0; $i < $count - 10; $i++) {
            $user = new Users();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, $user->getEmail()));
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }
        for ($i = 11; $i < $count - 5; $i++) {
            $user = new Users();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_SUPPORT']);
            $user->setPassword($this->hasher->hashPassword($user, $user->getEmail()));
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();

        /* tickets */
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < $count; $i++) {
            $ticket = new Tickets();
            $ticket->setTitle($faker->word());
            $ticket->setDescription($faker->text(200));
            $assignedUser = $users[array_rand($users)];
            $ticket->setAssignedTo($assignedUser);
            $priorities = TicketPriorityType::cases();
            $randomPriority = $priorities[array_rand($priorities)];
            $ticket->setPriority($randomPriority);

            $statuses = TicketStatusType::cases();
            $randomStatus = $statuses[array_rand($statuses)];
            $ticket->setStatus($randomStatus);
            $ticket->setCreatedBy($users[array_rand($users)]);

            $manager->persist($ticket);
        }

        $manager->flush();
    }
}
