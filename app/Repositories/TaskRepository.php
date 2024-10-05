<?php

namespace App\Repositories;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\Contracts\ITaskRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TaskRepository.
 */
class TaskRepository implements ITaskRepository
{
    /**
     * Get the tasks.
     * @param Project $project
     * @return Collection The tasks
     */
    public function index(Project $project): Collection
    {
        return Task::query()
            ->where('project_id', $project->id)
            ->get();
    }

    /**
     * Store the task.
     * @param TaskRequest $request
     * @param Project $project
     * @return Task The task
     */
    public function store(TaskRequest $request, Project $project): Task
    {
        return Task::create(array_merge($request->only(['name', 'description', 'status']), ['project_id' => $project->id]));
    }

    /**
     * Update the task.
     * @param TaskRequest $request
     * @param Task $task
     * @return Task The task
     */
    public function update(TaskRequest $request, Task $task): Task
    {
        $task->update($request->only(['name', 'description', 'status']));

        return $task;
    }

    /**
     * Delete the task.
     * @param Task $task
     * @return array
     */
    public function destroy(Task $task) :array
    {
        // Delete the task
        $task->delete();

        return [
            'status' => 1,
            'message' => 'The task deleted successfully!'
        ];
    }
}
