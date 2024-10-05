@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Details</h2>
<div id="projectList" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-3xl font-bold mb-4" id="projectName">Project: {{ $project->name }}</h1>
    <p class="text-lg mb-8">{{ $project->description }}</p>


    <h3 class="text-xl font-semibold mb-2">Add a new task</h3>
    <form id="addTaskForm" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" id="taskName" placeholder="Task Name" class="border p-2 rounded w-full" required>
        <input type="text" id="taskDescription" placeholder="Task Description" class="border p-2 rounded w-full" required>
        <select id="taskStatus" class="border p-2 rounded w-full" required>
            <option value="todo">Todo</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <button type="button" id="addTaskBtn" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-1">Add Task</button>
    </form>

    <h3 class="text-2xl font-semibold mb-4">Tasks</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-200 mb-6">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody id="tasksTable">
                <!-- Task rows will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Simple Edit Task Modal -->
<div id="editTaskModal" class="modal">
    <div class="modal-content">
        <span class="close text-gray-600 float-right cursor-pointer text-2xl">&times;</span>
        <h2 class="text-xl font-semibold mb-4">Edit Task</h2>
        <form id="editTaskForm">
            <input type="hidden" id="editTaskId">
            <div class="mb-4">
                <label for="editTaskName" class="block text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" id="editTaskName" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="editTaskDescription" class="block text-sm font-medium text-gray-700">Task Description</label>
                <input type="text" id="editTaskDescription" class="border p-2 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="editTaskStatus" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="editTaskStatus" class="border p-2 rounded w-full" required>
                    <option value="todo">Todo</option>
                    <option value="in-progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        const projectId = {{ $project->id }};

        // Get the token
        const token = localStorage.getItem('auth_token');

        // Check whether the user is logged in or not
        if (!token) {
            window.location.href = '/login';
        }

        // Set up headers for AJAX requests
        $.ajaxSetup({
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Fetch tasks for the project
        fetchTasks();

        // Function to fetch and display tasks
        function fetchTasks() {
            $.ajax({
                url: `/api/projects/${projectId}/tasks`,
                type: 'GET',
                success: function (tasks) {
                    const tasksTable = $('#tasksTable');
                    tasksTable.empty();

                    // Populate the table with tasks data
                    $.each(tasks, function (index, task) {
                        const taskRow = `
                            <tr data-id="${task.id}">
                                <td class="border px-4 py-2">${task.name}</td>
                                <td class="border px-4 py-2">${task.description}</td>
                                <td class="border px-4 py-2">${task.status}</td>
                                <td class="border px-4 py-2 text-center">
                                    <button class="edit-task bg-blue-500 text-white px-4 py-2 rounded mr-2" data-id="${task.id}">Edit</button>
                                    <button class="delete-task bg-red-500 text-white px-4 py-2 rounded" data-id="${task.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                        tasksTable.append(taskRow);
                    });
                },
                error: function () {
                    alert('Failed to load tasks.');
                }
            });
        }

        // Add new task
        $('#addTaskBtn').click(function () {
            const taskData = {
                name: $('#taskName').val(),
                description: $('#taskDescription').val(),
                status: $('#taskStatus').val()
            };

            $.ajax({
                url: `/api/projects/${projectId}/tasks`,
                type: 'POST',
                data: taskData,
                success: function () {
                    alert('Task added successfully!');
                    // Reload tasks
                    fetchTasks();
                    // Reset form fields
                    $('#addTaskForm')[0].reset();
                },
                error: function () {
                    alert('Failed to add task.');
                }
            });
        });

        // Delete task
        $(document).on('click', '.delete-task', function () {
            const taskId = $(this).data('id');

            if (confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: `/api/projects/${projectId}/tasks/${taskId}`,
                    type: 'DELETE',
                    success: function (response) {
                        alert(response.message);

                        // Reload tasks
                        fetchTasks();
                    },
                    error: function () {
                        alert('Failed to delete task.');
                    }
                });
            }
        });

        // Edit task
        $(document).on('click', '.edit-task', function () {
            const taskId = $(this).data('id');

            // Fetch the task details
            $.ajax({
                url: `/api/projects/${projectId}/tasks/${taskId}`,
                type: 'GET',
                success: function (task) {
                    // Populate the edit form
                    $('#editTaskId').val(task.id);
                    $('#editTaskName').val(task.name);
                    $('#editTaskDescription').val(task.description);
                    $('#editTaskStatus').val(task.status);
                    // Show the modal
                    $('#editTaskModal').fadeIn();
                },
                error: function () {
                    alert('Failed to load task details.');
                }
            });
        });

        // Handle edit task form submission
        $('#editTaskForm').submit(function (event) {
            event.preventDefault();

            const taskId = $('#editTaskId').val();
            const updatedTaskData = {
                name: $('#editTaskName').val(),
                description: $('#editTaskDescription').val(),
                status: $('#editTaskStatus').val()
            };

            $.ajax({
                url: `/api/projects/${projectId}/tasks/${taskId}`,
                type: 'PUT',
                data: updatedTaskData,
                success: function () {
                    alert('Task updated successfully!');
                    // Reload tasks
                    fetchTasks();
                    // Hide the modal
                    $('#editTaskModal').fadeOut();
                },
                error: function () {
                    alert('Failed to update task.');
                }
            });
        });

        // Close modal when the close button is clicked
        $('.close').click(function() {
            $('#editTaskModal').fadeOut();
        });

        // Close modal when clicking outside of the modal
        $(window).click(function(event) {
            if ($(event.target).is('#editTaskModal')) {
                $('#editTaskModal').fadeOut();
            }
        });
    });
</script>

@endsection
