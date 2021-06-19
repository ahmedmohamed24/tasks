<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    /**
     *  return all projects.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $projects = Auth::user()->availableProjects()->paginate(9);

        return view('projects.all', ['projects' => $projects]);
    }

    public function store(Request $request)
    {
        $project = Auth::user()->projects()->create($this->validateRequest($request));

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('show', $project);

        return view('projects.project', compact('project'));
    }

    public function updateNotes(Project $project, Request $request)
    {
        $this->authorize('update', $project);
        $notes = $request->validate([
            'notes' => 'nullable|string',
        ]);
        $project->update([
            'notes' => $notes['notes'],
        ]);
        //may return message of success
        return back();
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($this->validateRequest($request));
        //may return a success message
        return back();
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('destroy', $project);
        DB::beginTransaction();

        try {
            foreach ($project->tasks as $task) {
                $task->activity()->delete();
            }
            $project->tasks()->delete();
            $project->activity()->delete();
            $project->delete();
            DB::commit();

            return redirect('/project');
        } catch (Exception $e) {
            //add it to logs and return back
            DB::rollback();

            return redirect('/project')->withErrors($e->getMessage());
        }
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:244'],
            'description' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}