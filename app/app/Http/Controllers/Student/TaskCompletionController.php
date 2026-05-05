<?php

namespace App\Http\Controllers\Student;

use App\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Models\TaskCompletionSystem;
use App\Models\TaskCompletionRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCompletionController extends Controller
{
    /**
     * Display list of assigned tasks for student
     */
    public function myTasks()
    {
        $tasks = TaskCompletionSystem::where('student_id', Auth::id())
            ->with(['form.formSubmissions', 'assignedBy'])
            ->orderBy('deadline', 'asc')
            ->paginate(15);

        return view('student.task_completion_system.my-tasks', compact('tasks'));
    }

    /**
     * View specific task with remarks
     */
    public function viewTask(TaskCompletionSystem $taskCompletion)
    {
        if ($taskCompletion->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $taskCompletion->load(['form.formSubmissions', 'assignedBy', 'questionRemarks.question']);
        return view('student.task_completion_system.view-task', compact('taskCompletion'));
    }

    /**
     * Respond to mentor remarks
     */
    public function respondToRemark(Request $request, TaskCompletionRemark $remark)
    {
        // Verify student ownership
        if ($remark->taskCompletion->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_response' => 'required|string',
        ]);

        $validated['responded_at'] = now();
        $remark->update($validated);

        Notification::notifyMessage(
            user: $remark->taskCompletion->assigned_by,
            title: Auth::user()->name . ' responded to remark.',
            message: $request->student_response,
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($remark)
            ->causedBy(auth()->user())
            ->log('Response sent');

        return redirect()->route('task-completion.view', $remark->taskCompletion)
            ->with('success', 'Response submitted successfully!');
    }

    /**
     * Add overall response to task remarks
     */
    public function respondToOverallRemarks(Request $request, TaskCompletionSystem $taskCompletion)
    {
        // Ensure student can only respond to their own tasks
        if ($taskCompletion->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_response_to_remarks' => 'required|string',
        ]);

        $taskCompletion->update($validated);

        Notification::notifyMessage(
            user: $taskCompletion->assigned_by,
            title: Auth::user()->name . ' responded to overall remark.',
            message: $request->student_response_to_remarks,
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($taskCompletion)
            ->causedBy(auth()->user())
            ->log('Task over all remark');

        return redirect()->route('task-completion.view', $taskCompletion)
            ->with('success', 'Overall response submitted successfully!');
    }

    /**
     * Mark task as in progress
     */
    public function markInProgress(TaskCompletionSystem $taskCompletion)
    {
        if ($taskCompletion->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $taskCompletion->update(['status' => 'in_progress']);

        return redirect()->route('task-completion.view', $taskCompletion)
            ->with('success', 'Task marked as in progress!');
    }

    /**
     * Submit task
     */
    public function submitTask(TaskCompletionSystem $taskCompletion)
    {
        if ($taskCompletion->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $taskCompletion->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        Notification::notifyMessage(
            user: $taskCompletion->assigned_by,
            title: Auth::user()->name . ' completed task successfully.',
            message: 'submitted',
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($taskCompletion)
            ->causedBy(auth()->user())
            ->log('Task over all remark');

        return redirect()->route('task-completion.view', $taskCompletion)
            ->with('success', 'Task submitted successfully!');
    }
}
