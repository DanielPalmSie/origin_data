<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadCompanies($manager);
        $this->loadProjects($manager);
        $this->loadEmployees($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $roles = [
            ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName('User ' . $i);
            $user->setEmail('user' . $i . '@example.com');

            $plaintextPassword = 'test';
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);

            $user->setRoles($roles[$i % count($roles)]);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadCompanies(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $company = new Company();
            $company->setName('Company ' . $i);

            $manager->persist($company);
        }

        $manager->flush();
    }

    private function loadProjects(ObjectManager $manager): void
    {
        $companies = $manager->getRepository(Company::class)->findAll();

        foreach ($companies as $company) {
            for ($i = 0; $i < 10; $i++) {
                $project = new Project();
                $project->setName('Project ' . $i . ' for ' . $company->getName());
                $project->setCompany($company);

                $manager->persist($project);
            }
        }

        $manager->flush();
    }

    private function loadEmployees(ObjectManager $manager): void
    {
        $companies = $manager->getRepository(Company::class)->findAll();

        foreach ($companies as $company) {
            for ($i = 0; $i < 10; $i++) {
                $employee = new Employee();
                $employee->setName('Employee ' . $i . ' at ' . $company->getName());
                $employee->setCompany($company);

                $manager->persist($employee);
            }
        }

        $manager->flush();
    }
}