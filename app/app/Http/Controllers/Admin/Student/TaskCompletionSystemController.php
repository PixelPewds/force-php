<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\TaskCompletionSystem;
use App\Models\TaskCompletionRemark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\Notification;

class TaskCompletionSystemController extends Controller
{
    /**
     * Display a listing of assigned tasks
     */
    public function index()
    {
        $tasks = TaskCompletionSystem::with(['student', 'form', 'assignedBy'])
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->through(function ($task) {
                $task->submission = FormSubmission::where('form_id', $task->form_id)
                    ->where('student_id', $task->student_id)
                    ->first();
                return $task;
            });

        return view('admin.students.task_completion_system.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task assignment
     */
    public function create()
    {
        $students = User::role('Student')->orderBy('name')->get();
        $forms = Form::where('visibility', 0)->orderBy('title')->get();

        return view('admin.students.task_completion_system.create', compact('students', 'forms'));
    }

    /**
     * Store a newly created task assignment in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:forms,id',
            'deadline' => 'nullable|date|after_or_equal:today',
            'admin_remarks' => 'nullable|string',
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['assigned_at'] = now();
        $validated['status'] = 'pending';

        $task = TaskCompletionSystem::create($validated);

        Notification::notifyMessage(
            user: $request->student_id,
            title: Auth::user()->name . ' has assigned new task to you.',
            message: $task->form->title,
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($task)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $request->student_id])
            ->log('Task assigned successfully!');

        return redirect()->route('task-completion.index')
            ->with('success', 'Task assigned successfully!');
    }

    /**
     * Display the specified task
     */
    public function show(TaskCompletionSystem $taskCompletion)
    {
        $taskCompletion->load([
            'student',
            'assignedBy',
            'questionRemarks.question',
            'form' => function ($q) use ($taskCompletion) {
                $q->with([
                    'formSubmissions' => function ($subQ) use ($taskCompletion) {
                        $subQ->where('student_id', $taskCompletion->student_id);
                        $subQ->where('form_id', $taskCompletion->form_id)
                            ->with([
                                'responses.option',
                                'responses.question'
                            ]);
                    }
                ]);
            }
        ]);
        return view('admin.students.task_completion_system.show', compact('taskCompletion'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(TaskCompletionSystem $taskCompletion)
    {
        $taskCompletion->load(['student', 'form']);
        $students = User::role('Student')->orderBy('name')->get();
        $forms = Form::where('visibility', 0)->orderBy('title')->get();

        activity()
            ->causedBy(auth()->user())
            ->log('Task edit clicked!');

        return view('admin.students.task_completion_system.edit', compact('taskCompletion', 'students', 'forms'));
    }

    /**
     * Update the specified task in storage
     */
    public function update(Request $request, TaskCompletionSystem $taskCompletion)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'form_id' => 'required|exists:forms,id',
            'deadline' => 'nullable|date|after_or_equal:today',
            'admin_remarks' => 'nullable|string',
            'overall_remarks' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,submitted,completed,overdue',
        ]);

        $task = $taskCompletion->update($validated);

        Notification::notifyMessage(
            user: $request->student_id,
            title: Auth::user()->name . ' has updated task.',
            message: "New Form",
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($taskCompletion)
            ->causedBy(auth()->user())
            ->log('Task updated successfully');

        return redirect()->route('task-completion.show', $taskCompletion)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage
     */
    public function destroy(TaskCompletionSystem $taskCompletion)
    {
        activity()
            ->performedOn($taskCompletion)
            ->causedBy(auth()->user())
            ->log('Task deleted successfully');

        $taskCompletion->delete();

        return redirect()->route('task-completion.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Add remark for a specific question
     */
    public function addQuestionRemark(Request $request, TaskCompletionSystem $taskCompletion)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'admin_remark' => 'required|string',
        ]);

        TaskCompletionRemark::updateOrCreate(
            [
                'task_completion_id' => $taskCompletion->id,
                'question_id' => $validated['question_id'],
            ],
            ['admin_remark' => $validated['admin_remark']]
        );

        Notification::notifyMessage(
            user: $taskCompletion->student_id,
            title: Auth::user()->name . ' has added feedback.',
            message: $validated['admin_remark'],
            actionUrl: route('task-completion.my-tasks')
        );

        activity()
            ->performedOn($taskCompletion)
            ->causedBy(auth()->user())
            ->log('Task updated successfully');

        return redirect()->route('task-completion.show', $taskCompletion)
            ->with('success', 'Remark added successfully!');
    }

    /**
     * Add overall remark for the task
     */
    public function addOverallRemark(Request $request, TaskCompletionSystem $taskCompletion)
    {
        $validated = $request->validate([
            'overall_remarks' => 'required|string',
        ]);

        $taskCompletion->update([
            'overall_remarks' => $validated['overall_remarks'],
            'admin_remarks' => $validated['overall_remarks']
        ]);

        // Send mentor feedback notification to student
        Notification::notifyMentorFeedback(
            user: $taskCompletion->student_id,
            mentorName: Auth::user()->name,
            feedback: $validated['overall_remarks'],
            actionUrl: route('task-completion.my-tasks')
        );

        return redirect()->route('task-completion.show', $taskCompletion)
            ->with('success', 'Remark added successfully!');
    }

    /**
     * Track deadline status for all tasks
     */
    public function trackDeadlines()
    {
        $tasks = TaskCompletionSystem::where('deadline', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->update(['status' => 'overdue']);

        $overdueTasks = TaskCompletionSystem::where('status', 'overdue')
            ->with(['student', 'form', 'assignedBy'])
            ->paginate(15);

        return view('admin.students.task_completion_system.deadlines', compact('overdueTasks'));
    }
}
