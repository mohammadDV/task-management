<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface IProjectRepository.
 */
interface IProjectRepository
{
    /**
     * Get the projects.
     *
     * @return LengthAwarePaginator The projects
     */
    public function index(): LengthAwarePaginator;

    /**
     * Store the project.
     * @param ProjectRequest $request
     * @return Project The project
     */
    public function store(ProjectRequest $request): Project;

    /**
     * Delete the project.
     * @param Project $project
     * @return array
     */
    public function destroy(Project $project) :array;
}
