<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function feed(Request $r)
    {
        $q = Announcement::query();
        if ($r->filled('project_id')) $q->where('project_id', $r->integer('project_id'));
        if ($r->filled('after')) $q->where('created_at', '>', $r->date('after'));
        return $q->orderByDesc('created_at')->limit(50)->get();
    }
}
