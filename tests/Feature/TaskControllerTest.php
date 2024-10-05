<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\ITaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Get the user
        $this->user = User::factory()->create();

        // Mock the repository
        $this->repository = Mockery::mock(ITaskRepository::class);
        $this->app->instance(ITaskRepository::class, $this->repository);
    }

    /** @test */
    public function return_tasks_for_a_project()
    {

        // Create a project
        $project = Project::factory()->create();
        // Add tasks to project
        $tasks = Task::factory()->count(3)->make(['project_id' => $project->id]);

        // Simulate the index method's response
        $this->repository->shouldReceive('index')
            ->with(Mockery::on(function ($arg) use ($project) {
                return $arg->id === $project->id;
            }))
            ->once()
            ->andReturn($tasks);

        // Simulate an authenticated request
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('projects.tasks.index', ['project' => $project->id]));

        // Assert that the response status is OK and contains the expected tasks
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($tasks->toArray());
    }

    /** @test */
    public function store_a_new_task()
    {
        $project = Project::factory()->create();
        $taskData = Task::factory()->make(['project_id' => $project->id]);

        // Simulate the index method's response
        $this->repository->shouldReceive('store')
            ->once()
            ->andReturn($taskData);

        // Simulate an authenticated request
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('projects.tasks.store', ['project' => $project->id]), $taskData->toArray());

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson($taskData->toArray());
    }

    /** @test */
    public function return_a_specific_task()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        // Simulate an authenticated request
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('projects.tasks.show', ['project' => $project->id, 'task' => $task->id]));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($task->toArray());
    }

    /** @test */
    public function update_a_task()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $updatedTaskData = Task::factory()->make(['title' => 'Updated Title'])->toArray();

        $this->repository->shouldReceive('update')
            ->withAnyArgs()
            ->once()
            ->andReturn($task);

        // Simulate an authenticated request
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('projects.tasks.update', ['project' => $project->id, 'task' => $task->id]), $updatedTaskData);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonFragment($task->toArray());
    }

    /** @test */
    public function delete_a_task()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->repository->shouldReceive('destroy')
            ->once()
            ->andReturn([
                'status' => 1,
                'message' => 'The task deleted successfully!'
            ]);

        // Simulate an authenticated request
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('projects.tasks.destroy', ['project' => $project->id, 'task' => $task->id]));

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'status' => 1,
                'message' => 'The task deleted successfully!'
            ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
