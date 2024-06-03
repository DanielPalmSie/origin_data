<?php

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/project')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService)
    {}

    #[Route('/', name: 'project_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $projects = $this->projectService->getAllProjects();
        $data = array_map(function($project) {
            return [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'company' => $project->getCompany()->getId(),
            ];
        }, $projects);

        return $this->json($data);
    }

    #[Route('/', name: 'project_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $project = $this->projectService->createProject($data['name'] ?? '', $data['companyId'] ?? 0);

        if (!$project) {
            return $this->json(['status' => 'Company not found'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'status' => 'Project created!',
            'project' => [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'company' => $project->getCompany()->getId(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'project_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $project = $this->projectService->getProject($id);

        if (!$project) {
            return $this->json(['status' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $project->getId(),
            'name' => $project->getName(),
            'company' => $project->getCompany()->getId(),
        ]);
    }

    #[Route('/{id}', name: 'project_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $project = $this->projectService->updateProject($id, $data['name'] ?? '');

        if (!$project) {
            return $this->json(['status' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'Project updated!',
            'project' => [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'company' => $project->getCompany()->getId(),
            ]
        ]);
    }

    #[Route('/{id}', name: 'project_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->projectService->deleteProject($id);

        if (!$deleted) {
            return $this->json(['status' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['status' => 'Project deleted']);
    }
}