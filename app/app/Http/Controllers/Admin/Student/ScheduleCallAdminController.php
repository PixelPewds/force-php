<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Controllers\Controller;
use App\Models\ScheduleCall;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleCallAdminController extends Controller
{
    /**
     * Display all scheduled calls for admin.
     */
    public function index(Request $request)
    {
        $query = ScheduleCall::with('student');

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('scheduled_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('scheduled_at', '<=', $request->to_date);
        }

        $scheduledCalls = $query->orderBy('scheduled_at', 'desc')->paginate(15);
        $students = User::role('Student')->where('status', 'Y')->get();

        return view('admin.schedule-calls.index', compact('scheduledCalls', 'students'));
    }

    /**
     * Show details of a specific scheduled call.
     */
    public function show(ScheduleCall $scheduleCall)
    {
        return view('admin.schedule-calls.show', compact('scheduleCall'));
    }

    /**
     * Update the status of a scheduled call.
     */
    public function updateStatus(Request $request, ScheduleCall $scheduleCall)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $scheduleCall->update($validated);

        return redirect()->back()->with('success', 'Call status updated successfully!');
    }

    /**
     * Get scheduled calls by student (for filtering).
     */
    public function getByStudent(User $student)
    {
        $calls = ScheduleCall::where('student_id', $student->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return response()->json($calls);
    }
}
