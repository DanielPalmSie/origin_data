<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {

        $roles = [
            ['ROLE_USER', 'ROLE_DEVICE_VIEW', 'ROLE_ROOM_CREATE'],
            ['ROLE_ADMIN', 'ROLE_AUTOMATION_RULE_CREATE', 'ROLE_ENERGY_PRICE_VIEW'],
            ['ROLE_SUPER_ADMIN', 'ROLE_USER_CREATE', 'ROLE_DEVICE_CREATE', 'ROLE_SCHEDULED_TASK_CREATE'],

        ];

        // Creating and persisting User entities
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName('User ' . $i);
            $user->setEmail('user' . $i . '@example.com');
            // Hash the password
            $plaintextPassword = 'test'; // Replace 'test' with the chosen plain password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);

            // ... set other User fields
            // Assign roles to the user. Here we cycle through the predefined roles array.
            $user->setRoles($roles[$i % count($roles)]);

            $manager->persist($user);
            $users[] = $user;
        }

        // Flush to ensure User entities are persisted before trying to use them
        $manager->flush();
    }
}
