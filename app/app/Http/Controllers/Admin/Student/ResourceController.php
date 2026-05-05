<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceRemark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Facades\Notification;

class ResourceController extends Controller
{
    /**
     * Display a listing of assigned resources
     */
    public function index()
    {
        $resources = Resource::with(['student', 'assignedBy'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(15);

        return view('admin.students.resource_center.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource assignment
     */
    public function create()
    {
        $students = User::role('Student')->orderBy('name')->get();
        $resourceTypes = ['pdf', 'video', 'article', 'workbook', 'media'];

        return view('admin.students.resource_center.create', compact('students', 'resourceTypes'));
    }

    /**
     * Store a newly created resource assignment in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'resource_type' => 'required|in:pdf,video,article,workbook,media',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx',
            'resource_url' => 'nullable|url',
            'deadline' => 'nullable|date|after_or_equal:today',
            'admin_remarks' => 'nullable|string',
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['assigned_at'] = now();
        $validated['status'] = 'pending';

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('resources', 'public');
        }

        $resource = Resource::create($validated);

        Notification::notifyMessage(
            user: $resource->student_id,
            title: 'Assignment Added',
            message: $resource->title,
            actionUrl: route('resource.view', $resource->id)
        );

        activity()
            ->performedOn($resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $resource->student_id])
            ->log(Auth::user()->name . ' Assignment Added');

        return redirect()->route('resource.index')
            ->with('success', 'Resource assigned successfully!');
    }

    /**
     * Display the specified resource
     */
    public function show(Resource $resource)
    {
        $resource->load(['student', 'assignedBy', 'remarks']);

        return view('admin.students.resource_center.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Resource $resource)
    {
        $resource->load(['student']);
        $students = User::role('Student')->orderBy('name')->get();
        $resourceTypes = ['pdf', 'video', 'article', 'workbook', 'media'];

        return view('admin.students.resource_center.edit', compact('resource', 'students', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'resource_type' => 'required|in:pdf,video,article,workbook,media',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx',
            'resource_url' => 'nullable|url',
            'deadline' => 'nullable|date|after_or_equal:today',
            'admin_remarks' => 'nullable|string',
            'overall_remarks' => 'nullable|string',
            'status' => 'nullable|in:pending,accessed,completed',
        ]);

        // If this is a draft resource (cloned), activate it
        if ($resource->status === '') {
            $validated['status'] = 'pending';
            $validated['assigned_at'] = now();
            $validated['assigned_by'] = Auth::id();
        }

        // Handle file upload
        if ($request->hasFile('file_path')) {
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('resources', 'public');
        }

        $resource->update($validated);

        Notification::notifyMessage(
            user: $resource->student_id,
            title: 'Assignment Updated',
            message: $resource->title,
            actionUrl: route('resource.view', $resource->id)
        );

        activity()
            ->performedOn($resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $resource->student_id])
            ->log(Auth::user()->name . ' Assignment Updated');

        return redirect()->route('resource.show', $resource)
            ->with('success', 'Resource updated successfully!');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Resource $resource)
    {
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        activity()
            ->performedOn($resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $resource->student_id])
            ->log(Auth::user()->name . ' Assignment Deleted');

        $resource->delete();

        return redirect()->route('resource.index')
            ->with('success', 'Resource deleted successfully!');
    }

    /**
     * Add remark for resource
     */
    public function addRemark(Request $request, Resource $resource)
    {
        if ($request->has('overall_remarks')) {
            $validated = $request->validate([
                'overall_remarks' => 'required|string',
            ]);

            $resource->update([
                'overall_remarks' => $validated['overall_remarks'],
            ]);

            Notification::notifyMessage(
                user: $resource->student_id,
                title: 'Overall Feedback',
                message: $resource->title,
                actionUrl: route('resource.view', $resource->id)
            );

            activity()
                ->performedOn($resource)
                ->causedBy(auth()->user())
                ->withProperties(['id' => $resource->student_id])
                ->log(Auth::user()->name . ' Added remark');

        } else {
            $validated = $request->validate([
                'admin_remark' => 'required|string',
            ]);

            $resourceRemark = ResourceRemark::create([
                'resource_id' => $resource->id,
                'admin_remark' => $validated['admin_remark'],
            ]);

            // Send notification about remark on the resource
            Notification::notifyMessage(
                user: $resource->student_id,
                title: 'Remark',
                message: $validated['admin_remark'],
                actionUrl: null
            );
        }

        return redirect()->route('resource.show', $resource)
            ->with('success', 'Feedback added successfully!');
    }

    /**
     * Clone a resource for assignment to another student
     */
    public function clone(Resource $resource)
    {
        $clonedResource = $resource->replicate();
        $clonedResource->student_id = null;
        $clonedResource->status = '';
        $clonedResource->assigned_at = null;
        $clonedResource->save();

        activity()
            ->performedOn($clonedResource)
            ->causedBy(auth()->user())
            ->withProperties(['original_student_id' => $resource->student_id])
            ->log(Auth::user()->name . ' Cloned Resource (Template)');

        return redirect()->route('resource.edit', $clonedResource)
            ->with('success', 'Resource cloned successfully! Select a student to assign the resource.');
    }

    /**
     * Track deadline status for all resources
     */
    public function trackDeadlines()
    {
        Resource::where('deadline', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->update(['status' => 'pending']);

        $overdueResources = Resource::where('deadline', '<', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->with(['student', 'assignedBy'])
            ->paginate(15);

        return view('admin.students.resource_center.deadlines', compact('overdueResources'));
    }
}
