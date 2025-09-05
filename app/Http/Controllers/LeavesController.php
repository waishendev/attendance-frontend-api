<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveRequest;

class LeavesController extends Controller
{
    public function index(Request $r)
    {
        $emp = Employee::where('user_id', auth('api')->id())->first();
        if (!$emp) return response()->json(['data'=>[],'total'=>0]);

        $q = LeaveRequest::where('employee_id',$emp->id);
        if ($r->filled('status')) $q->where('status',$r->string('status'));
        return response()->json($q->orderByDesc('id')->paginate($r->integer('per_page',20)));
    }

    public function store(Request $r)
    {
        $emp = Employee::where('user_id', auth('api')->id())->firstOrFail();
        $data = $r->validate([
            'type'=>['required','in:annual,sick,unpaid,other'],
            'start_at'=>['required','date'],
            'end_at'=>['required','date','after_or_equal:start_at'],
            'reason'=>['nullable','string'],
        ]);
        $data['employee_id'] = $emp->id;
        $leave = LeaveRequest::create($data);
        return response()->json($leave, 201);
    }

    public function cancel($id)
    {
        $emp = Employee::where('user_id', auth('api')->id())->firstOrFail();
        $leave = LeaveRequest::where('employee_id',$emp->id)->findOrFail($id);
        if ($leave->status !== 'pending') {
            return response()->json(['message'=>'Only pending can be cancelled'], 422);
        }
        $leave->status = 'rejected';
        $leave->save();
        return $leave->fresh();
    }
}
