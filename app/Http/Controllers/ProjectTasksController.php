<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        $validated = request()->validate(
            ['body' => 'required']
        );

        $project->addTask($validated);

        return redirect($project->path());
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Project $project, Task $task)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        request()->validate(['body' => 'required']);

        $task->update(
            [
                'body' => request('body'),
                'completed' => request()->has('completed')
            ]
        );

        return redirect($project->path());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
