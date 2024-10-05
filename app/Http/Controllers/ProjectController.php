<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Get the projects.
     * @return View the project
     */
    public function show($id) :View
    {
        // Get the project
        $project = Project::findOrFail($id);

        return view('projects.details', compact('project'));
    }
}
