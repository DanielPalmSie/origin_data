<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompanyService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyRepository $companyRepository
    )
    {}

    public function getAllCompanies(): array
    {
        return $this->companyRepository->findAll();
    }

    public function createCompany(string $name): Company
    {
        $company = new Company();
        $company->setName($name);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $company;
    }

    public function getCompany(int $id): ?Company
    {
        return $this->companyRepository->find($id);
    }

    public function updateCompany(int $id, string $name): ?Company
    {
        $company = $this->companyRepository->find($id);

        if ($company) {
            $company->setName($name);
            $this->entityManager->flush();
        }

        return $company;
    }

    public function deleteCompany(int $id): bool
    {
        $company = $this->companyRepository->find($id);

        if ($company) {
            $this->entityManager->remove($company);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }
}