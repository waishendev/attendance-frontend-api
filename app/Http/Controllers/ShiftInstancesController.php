<?php

namespace App\Http\Controllers;

use App\Models\ShiftInstance;
use Illuminate\Http\Request;

class ShiftInstancesController extends Controller
{
    public function index(Request $r)
    {
        $q = ShiftInstance::query();
        if ($r->filled('project_id')) $q->where('project_id', $r->integer('project_id'));
        if ($r->filled('location_id')) $q->where('location_id', $r->integer('location_id'));
        if ($r->filled('date_from')) $q->whereDate('start_at', '>=', $r->date('date_from'));
        if ($r->filled('date_to')) $q->whereDate('start_at', '<=', $r->date('date_to'));
        return $q->orderBy('start_at')->paginate($r->integer('per_page', 20));
    }
}
