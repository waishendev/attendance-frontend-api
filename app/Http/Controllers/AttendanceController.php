<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Employee;
use App\Models\AttendanceLog;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $user = auth('api')->user();
        $emp = Employee::where('user_id', $user->id)->first();
        if (! $emp) {
            return response()->json(['message' => 'Employee not bound'], 422);
        }

        $today = Carbon::now()->toDateString();
        $log = AttendanceLog::firstOrCreate(
            ['employee_id' => $emp->id, 'work_date' => $today],
            ['status' => 'normal']
        );
        if (! $log->check_in_at) {
            $log->check_in_at = Carbon::now();
            $log->save();
        }
        return response()->json($log->fresh());
    }

    public function checkOut(Request $request)
    {
        $user = auth('api')->user();
        $emp = Employee::where('user_id', $user->id)->first();
        if (! $emp) {
            return response()->json(['message' => 'Employee not bound'], 422);
        }

        $today = Carbon::now()->toDateString();
        $log = AttendanceLog::where('employee_id', $emp->id)
            ->where('work_date', $today)
            ->first();
        if (! $log) {
            $log = AttendanceLog::create([
                'employee_id' => $emp->id,
                'work_date' => $today,
                'status' => 'normal',
                'check_out_at' => Carbon::now(),
            ]);
        } else {
            $log->check_out_at = Carbon::now();
            $log->save();
        }
        return response()->json($log->fresh());
    }

    public function myLogs(Request $request)
    {
        $user = auth('api')->user();
        $emp = Employee::where('user_id', $user->id)->first();
        if (! $emp) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $q = AttendanceLog::where('employee_id', $emp->id);

        if ($request->filled('date_from')) {
            $q->whereDate('work_date', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $q->whereDate('work_date', '<=', $request->date('date_to'));
        }

        $logs = $q->orderByDesc('work_date')->paginate($request->integer('per_page', 20));
        return response()->json($logs);
    }
}
