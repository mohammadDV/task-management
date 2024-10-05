<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\Contracts\IProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     *
     * @param IProjectRepository $repository The repository
     */
    public function __construct(protected IProjectRepository $repository)
    {
        //
    }

    /**
     * Get the projects.
     * @return JsonResponse the projects
     */
    public function index() :JsonResponse
    {
        return response()->json($this->repository->index(), Response::HTTP_OK);

    }

    /**
     * Store the project.
     * @param ProjectRequest $request
     * @return JsonResponse the project
    */
    public function store(ProjectRequest $request) :JsonResponse
    {
        return response()->json($this->repository->store($request), Response::HTTP_CREATED);
    }

    /**
     * Delete the project.
     * @param Project $project
     * @return JsonResponse the projects
     */
    public function destroy(Project $project) :JsonResponse
    {
        return response()->json($this->repository->destroy($project), Response::HTTP_OK);
    }
}
