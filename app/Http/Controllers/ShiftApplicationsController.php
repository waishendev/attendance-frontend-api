<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ShiftApplication;
use Illuminate\Http\Request;

class ShiftApplicationsController extends Controller
{
    protected function employee()
    {
        return Employee::where('user_id', auth('api')->id())->firstOrFail();
    }

    public function store(Request $r)
    {
        $emp = $this->employee();
        $data = $r->validate([
            'shift_instance_id' => ['required', 'exists:shift_instances,id'],
        ]);
        $application = ShiftApplication::firstOrCreate(
            ['employee_id' => $emp->id, 'shift_instance_id' => $data['shift_instance_id']],
            ['status' => 'pending']
        );
        $status = $application->wasRecentlyCreated ? 201 : 200;
        return response()->json($application, $status);
    }

    public function my(Request $r)
    {
        $emp = $this->employee();
        $q = ShiftApplication::where('employee_id', $emp->id);
        if ($r->filled('status')) $q->where('status', $r->string('status'));
        return $q->orderByDesc('id')->paginate($r->integer('per_page', 20));
    }

    public function cancel($id)
    {
        $emp = $this->employee();
        $app = ShiftApplication::where('employee_id', $emp->id)->findOrFail($id);
        if ($app->status !== 'pending') {
            return response()->json(['message' => 'Only pending can be cancelled'], 422);
        }
        $app->status = 'cancelled';
        $app->save();
        return $app->fresh();
    }
}
