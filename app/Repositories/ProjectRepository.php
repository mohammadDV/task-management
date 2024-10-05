<?php

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\Contracts\IProjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProjectRepository.
 */
class ProjectRepository implements IProjectRepository
{
    /**
     * Get the projects.
     *
     * @return LengthAwarePaginator The tasks
     */
    public function index(): LengthAwarePaginator
    {
        return Project::paginate(10);
    }

    /**
     * Store the project.
     * @param ProjectRequest $request
     * @return Project The project
     */
    public function store(ProjectRequest $request): Project
    {
        return Project::create($request->only(['name', 'description']));
    }

    /**
     * Delete the project.
     * @param Project $project
     * @return array
     */
    public function destroy(Project $project) :array
    {
        // Delete the project
        $project->delete();

        return [
            'status' => 1,
            'message' => 'The project deleted successfully!'
        ];
    }
}
