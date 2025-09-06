<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $r)
    {
        $q = Project::query();
        if ($r->filled('active')) {
            $q->where('active', $r->boolean('active'));
        }
        if ($r->filled('keyword')) {
            $q->where('name', 'like', '%' . $r->string('keyword') . '%');
        }
        return $q->orderBy('name')->paginate($r->integer('per_page', 20));
    }

    public function locations($id)
    {
        $project = Project::findOrFail($id);
        return $project->locations()->get();
    }
}
