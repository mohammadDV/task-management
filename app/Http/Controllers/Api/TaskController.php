<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\Contracts\ITaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * TaskController constructor.
     *
     * @param ITaskRepository $repository The repository
     */
    public function __construct(protected ITaskRepository $repository)
    {
        //
    }

    /**
     * Get the tasks.
     * @param Project $project
     * @return JsonResponse the tasks
    */
    public function index(Project $project) :JsonResponse
    {
        return response()->json($this->repository->index($project), Response::HTTP_OK);
    }

    /**
     * Store the task.
     * @param TaskRequest $request
     * @param Project $project
     * @return JsonResponse the task
    */
    public function store(TaskRequest $request, Project $project) :JsonResponse
    {
        return response()->json($this->repository->store($request, $project), Response::HTTP_CREATED);

    }

    /**
     * Show the task.
     * @param Project $project
     * @param Task $task
     * @return JsonResponse the tasks
    */
    public function show(Project $project, Task $task) :JsonResponse
    {
        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * Update the task.
     * @param TaskRequest $request
     * @param Project $project
     * @param Task $task
     * @return JsonResponse the tasks
    */
    public function update(TaskRequest $request, Project $project, Task $task) :JsonResponse
    {
        return response()->json($this->repository->update($request, $task), Response::HTTP_OK);
    }

    /**
     * Remove the task.
     * @param Project $project
     * @param Task $task
     * @return JsonResponse the tasks
    */
    public function destroy(Project $project, Task $task) :JsonResponse
    {
        return response()->json($this->repository->destroy($task), Response::HTTP_OK);

    }
}
