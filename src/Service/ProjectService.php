<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\CompanyRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectRepository $projectRepository,
        private readonly CompanyRepository $companyRepository,
    )
    {}

    public function getAllProjects(): array
    {
        return $this->projectRepository->findAll();
    }

    public function createProject(string $name, int $companyId): ?Project
    {
        $company = $this->companyRepository->find($companyId);

        if (!$company) {
            return null;
        }

        $project = new Project();
        $project->setName($name);
        $project->setCompany($company);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function getProject(int $id): ?Project
    {
        return $this->projectRepository->find($id);
    }

    public function updateProject(int $id, string $name): ?Project
    {
        $project = $this->projectRepository->find($id);

        if ($project) {
            $project->setName($name);
            $this->entityManager->flush();
        }

        return $project;
    }

    public function deleteProject(int $id): bool
    {
        $project = $this->projectRepository->find($id);

        if ($project) {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }
}