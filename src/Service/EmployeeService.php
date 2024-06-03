<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EmployeeRepository $employeeRepository,
        private readonly CompanyRepository $companyRepository)
    {}

    public function getAllEmployees(): array
    {
        return $this->employeeRepository->findAll();
    }

    public function createEmployee(string $name, int $companyId): ?Employee
    {
        $company = $this->companyRepository->find($companyId);

        if (!$company) {
            return null;
        }

        $employee = new Employee();
        $employee->setName($name);
        $employee->setCompany($company);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $employee;
    }

    public function getEmployee(int $id): ?Employee
    {
        return $this->employeeRepository->find($id);
    }

    public function updateEmployee(int $id, string $name): ?Employee
    {
        $employee = $this->employeeRepository->find($id);

        if ($employee) {
            $employee->setName($name);
            $this->entityManager->flush();
        }

        return $employee;
    }

    public function deleteEmployee(int $id): bool
    {
        $employee = $this->employeeRepository->find($id);

        if ($employee) {
            $this->entityManager->remove($employee);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }
}