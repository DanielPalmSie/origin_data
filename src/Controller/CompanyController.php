<?php

namespace App\Controller;

namespace App\Controller;

use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/company')]
class CompanyController extends AbstractController
{
    public function __construct(private readonly CompanyService $companyService)
    {}

    #[Route('/', name: 'company_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $companies = $this->companyService->getAllCompanies();
        $data = array_map(function($company) {
            return [
                'id' => $company->getId(),
                'name' => $company->getName(),
                'projects' => array_map(function($project) {
                    return [
                        'id' => $project->getId(),
                        'name' => $project->getName(),
                    ];
                }, $company->getProjects()->toArray()),
                'employees' => array_map(function($employee) {
                    return [
                        'id' => $employee->getId(),
                        'name' => $employee->getName(),
                    ];
                }, $company->getEmployees()->toArray()),
            ];
        }, $companies);

        return $this->json($data);
    }

    #[Route('/', name: 'company_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = $this->companyService->createCompany($data['name'] ?? '');

        return $this->json([
            'status' => 'Company created!',
            'company' => [
                'id' => $company->getId(),
                'name' => $company->getName(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'company_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->getCompany($id);

        if (!$company) {
            return $this->json(['status' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $company->getId(),
            'name' => $company->getName(),
        ]);
    }

    #[Route('/{id}', name: 'company_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = $this->companyService->updateCompany($id, $data['name'] ?? '');

        if (!$company) {
            return $this->json(['status' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'Company updated!',
            'company' => [
                'id' => $company->getId(),
                'name' => $company->getName(),
            ]
        ]);
    }

    #[Route('/{id}', name: 'company_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->companyService->deleteCompany($id);

        if (!$deleted) {
            return $this->json(['status' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['status' => 'Company deleted']);
    }
}