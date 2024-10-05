<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ITaskRepository.
 */
interface ITaskRepository
{
    /**
     * Get the tasks.
     * @param Project $project
     * @return Collection The tasks
     */
    public function index(Project $project): Collection;

    /**
     * Store the task.
     * @param TaskRequest $request
     * @param Project $project
     * @return Task The task
     */
    public function store(TaskRequest $request, Project $project): Task;

    /**
     * Update the task.
     * @param TaskRequest $request
     * @param Task $task
     * @return Task The task
     */
    public function update(TaskRequest $request, Task $task): Task;

    /**
     * Delete the task.
     * @param Task $task
     * @return array
     */
    public function destroy(Task $task) :array;
}
