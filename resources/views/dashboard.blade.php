@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
    <div id="projectList" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-xl font-semibold mb-2">Add a new project</h3>
        <form id="addProjectForm" class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" id="projectName" placeholder="Project Name" class="border p-2 rounded w-full" required>
            <input type="text" id="projectDescription" placeholder="Project Description" class="border p-2 rounded w-full" required>
            <button type="button" id="addProjectBtn" class="bg-green-500 text-white px-4 py-2 rounded col-span-1 md:col-span-1">Add Project</button>
        </form>
        <h3 class="text-2xl font-semibold mb-4">Projects</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-200 mb-6">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Project Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody id="projects">
                    <!-- Projects will be appended here -->
                </tbody>
            </table>
        </div>
        <!-- Pagination Controls -->
        <div id="pagination" class="flex justify-center items-center mt-4">
            <button id="prevPage" class="bg-gray-500 text-white py-1 px-3 rounded mx-2"><</button>
            <span id="currentPage" class="text-gray-700 mx-2">Page 1</span>
            <button id="nextPage" class="bg-blue-500 text-white py-1 px-3 rounded mx-2">></button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // The current page
        let currentPage = 1;
        const token = localStorage.getItem('auth_token');
        const projectTable = $('#projects');
        const currentPageDisplay = $('#currentPage');

        // Check whether the user is logged in or not
        if (!token) {
            window.location.href = '/login';
        }

        $.ajaxSetup({
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Fetch the projects
        function fetchProjects(page = 1) {
            $.ajax({
                url: `/api/projects?page=${page}`,
                type: 'GET',
                success: function(response) {
                    const { data, current_page, last_page } = response;

                    // Clear the table
                    projectTable.empty();

                    // Populate the table with project data
                    $.each(data, function(index, project) {
                        const row = `
                            <tr data-id="${project.id}">
                                <td class="border px-4 py-2">${project.name}</td>
                                <td class="border px-4 py-2">${project.description}</td>
                                <td class="py-2 border text-center">
                                    <a href="/projects/${project.id}" title="Details"><button class="view-details bg-blue-500 text-white px-4 py-2 rounded" data-id="${project.id}">Details</button></a>
                                    <button class="delete-project bg-red-500 text-white px-4 py-2 rounded" data-id="${project.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                        projectTable.append(row);
                    });

                    // Update the page display
                    currentPageDisplay.text(`Page ${current_page}`);
                    currentPage = current_page;

                    // Toggle button states
                    $('#prevPage').prop('disabled', current_page === 1);
                    $('#nextPage').prop('disabled', current_page === last_page);
                },
                error: function() {
                    projectTable.html('<tr><td colspan="2" class="text-center text-red-500">Failed to load projects</td></tr>');
                }
            });
        }

        // Fetch the first page of projects on load
        fetchProjects(currentPage);

        // Handle previous page click
        $('#prevPage').click(function () {
            if (currentPage > 1) {
                fetchProjects(currentPage - 1);
            }
        });

        // Handle next page click
        $('#nextPage').click(function () {
            fetchProjects(currentPage + 1);
        });

        // Add new project
        $('#addProjectBtn').click(function () {
            const projectData = {
                name: $('#projectName').val(),
                description: $('#projectDescription').val(),
            };

            // Check if the name and description fields are empty
            if (!projectData.name || !projectData.description) {
                alert('Project name and description cannot be empty.');
                return;
            }

            $.ajax({
                url: `/api/projects`,
                type: 'POST',
                data: projectData,
                success: function () {
                    alert('Project added successfully!');
                    // Reload projects
                    fetchProjects();
                    // Reset form fields
                    $('#addProjectForm')[0].reset();
                },
                error: function () {
                    alert('Failed to add project.');
                }
            });
        });

        // Handel delete the project
        $(document).on('click', '.delete-project', function () {

            // Get the ID of the project to delete
            let projectId = $(this).data('id');

            // Confirm before deletion
            if (confirm('Are you sure you want to delete this project?')) {
                $.ajax({
                    type: 'DELETE',
                    url: `/api/projects/${projectId}`,
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    success: function (response) {
                        // If the delete was successful, remove the row from the table
                        $(`tr[data-id='${projectId}']`).remove();
                        fetchProjects(currentPage);
                        alert(response.message);
                    },
                    error: function (error) {
                        // Handle errors
                        alert('An error occurred while deleting the project.');
                    }
                });
            }
        });
    });
</script>
@endsection
