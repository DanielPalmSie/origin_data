<?php

namespace App\Controller;

use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/employee')]
class EmployeeController extends AbstractController
{
    public function __construct(private readonly EmployeeService $employeeService)
    {}

    #[Route('/', name: 'employee_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getAllEmployees();
        $data = array_map(function($employee) {
            return [
                'id' => $employee->getId(),
                'name' => $employee->getName(),
                'company' => $employee->getCompany()->getId(),
            ];
        }, $employees);

        return $this->json($data);
    }

    #[Route('/', name: 'employee_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employee = $this->employeeService->createEmployee($data['name'] ?? '', $data['companyId'] ?? 0);

        if (!$employee) {
            return $this->json(['status' => 'Company not found'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'Employee created!',
            'employee' => [
                'id' => $employee->getId(),
                'name' => $employee->getName(),
                'company' => $employee->getCompany()->getId(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'employee_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployee($id);

        if (!$employee) {
            return $this->json(['status' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $employee->getId(),
            'name' => $employee->getName(),
            'company' => $employee->getCompany()->getId(),
        ]);
    }

    #[Route('/{id}', name: 'employee_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $employee = $this->employeeService->updateEmployee($id, $data['name'] ?? '');

        if (!$employee) {
            return $this->json(['status' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'Employee updated!',
            'employee' => [
                'id' => $employee->getId(),
                'name' => $employee->getName(),
                'company' => $employee->getCompany()->getId(),
            ]
        ]);
    }

    #[Route('/{id}', name: 'employee_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->employeeService->deleteEmployee($id);

        if (!$deleted) {
            return $this->json(['status' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['status' => 'Employee deleted']);
    }
}