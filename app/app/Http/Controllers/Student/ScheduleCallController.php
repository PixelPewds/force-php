<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ScheduleCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;

class ScheduleCallController extends Controller
{
    /**
     * Show the schedule call page with Calendly integration.
     */
    public function index()
    {
        $calendlyUrl = config('schedule-call.calendly_url');
        $studentId = auth()->id();

        // Get the latest scheduled calls for the student
        $scheduledCalls = ScheduleCall::where('student_id', $studentId)
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('student.schedule-call.index', compact('calendlyUrl', 'scheduledCalls'));
    }

    /**
     * Show the form for scheduling a call.
     */
    public function create()
    {
        $calendlyUrl = config('schedule-call.calendly_url');

        return view('student.schedule-call.create', compact('calendlyUrl'));
    }

    /**
     * Store a newly scheduled call (not used with webhooks, kept for compatibility).
     */
    public function store(Request $request)
    {
        return redirect()->route('student.schedule-call.index')
            ->with('info', 'Bookings are handled through Calendly. Please use the booking link above.');
    }

    /**
     * Display a specific scheduled call.
     */
    public function show(ScheduleCall $scheduleCall)
    {
        // Ensure the student can only view their own scheduled calls
        if ($scheduleCall->student_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('student.schedule-call.show', compact('scheduleCall'));
    }

    /**
     * Show the form for editing a scheduled call.
     */
    public function edit(ScheduleCall $scheduleCall)
    {
        // Ensure the student can only edit their own scheduled calls
        if ($scheduleCall->student_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $calendlyUrl = config('schedule-call.calendly_url');

        return view('student.schedule-call.edit', compact('scheduleCall', 'calendlyUrl'));
    }

    /**
     * Update a scheduled call.
     */
    public function update(Request $request, ScheduleCall $scheduleCall)
    {
        // Ensure the student can only update their own scheduled calls
        if ($scheduleCall->student_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'scheduled_at' => 'required|date_format:Y-m-d H:i',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $scheduleCall->update($validated);

        return redirect()->route('student.schedule-call.index')
            ->with('success', 'Call updated successfully!');
    }

    /**
     * Cancel a scheduled call.
     */
    public function destroy(ScheduleCall $scheduleCall)
    {
        // Ensure the student can only delete their own scheduled calls
        if ($scheduleCall->student_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $scheduleCall->update(['status' => 'cancelled']);

        return redirect()->route('student.schedule-call.index')
            ->with('success', 'Call cancelled successfully!');
    }
}
