<?php

namespace App\Http\Controllers\Student;

use App\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class ResourceCenterController extends Controller
{
    /**
     * Display list of resources assigned to student
     */
    public function myResources()
    {
        $resources = Resource::where('student_id', Auth::id())
            ->with(['assignedBy'])
            ->orderBy('assigned_at', 'desc')
            ->paginate(15);

        return view('student.resource_center.my-resources', compact('resources'));
    }

    /**
     * View specific resource with remarks
     */
    public function viewResource(Resource $resource)
    {
        if ($resource->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($resource->status === 'pending') {
            # code...
            $resource->update([
                'status' => 'accessed',
                'accessed_at' => now(),
                'completion_percentage' => 50
            ]);

            Notification::notifyMessage(
                user: $resource->assigned_by,
                title: Auth::user()->name . ' Resource Accessed',
                message: $resource->title,
                actionUrl: route('resource.show', $resource->id)
            );
        }

        activity()
            ->performedOn($resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $resource->student_id])
            ->log(Auth::user()->name . ' Resource Accessed');

        $resource->load(['assignedBy', 'remarks']);
        return view('student.resource_center.view-resource', compact('resource'));
    }

    /**
     * Mark resource as accessed
     */
    public function markAccessed(Resource $resource)
    {
        // if ($resource->student_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        // $resource->update([
        //     'status' => 'accessed',
        //     'accessed_at' => now(),
        //     'completion_percentage' => 50,
        // ]);

        // return redirect()->route('resource.view', $resource)
        //     ->with('success', 'Resource marked as accessed!');
    }

    /**
     * Mark resource as completed
     */
    public function markCompleted(Resource $resource)
    {
        if ($resource->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $resource->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completion_percentage' => 100,
            ]);

            Notification::notifyMessage(
                user: $resource->assigned_by,
                title: Auth::user()->name . ' Completed Completed',
                message: $resource->title,
                actionUrl: route('resource.show', $resource->id)
            );

            activity()
                ->performedOn($resource)
                ->causedBy(auth()->user())
                ->withProperties(['id' => $resource->student_id])
                ->log(Auth::user()->name . ' Resource Completed');
        } catch (\Throwable $th) {

        }

        return redirect()->back()->with('success', 'Resource marked as completed!');
    }

    /**
     * Respond to resource remark
     */
    public function respondToRemark(Request $request, ResourceRemark $remark)
    {
        if ($remark->resource->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_response' => 'required|string',
            'file_path' => 'nullable',
        ]);

        $validated['responded_at'] = now();

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('resources/student', 'public');
        }

        $remark->update($validated);

        Notification::notifyMessage(
            user: $remark->resource->assigned_by,
            title: Auth::user()->name . ' responded to remark',
            message: $remark->resource->title,
            actionUrl: route('resource.show', $remark->resource->id)
        );

        activity()
            ->performedOn($remark->resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $remark->resource->student_id])
            ->log(Auth::user()->name . ' responded to remark');

        return redirect()->route('resource.view', $remark->resource)
            ->with('success', 'Response submitted successfully!');
    }

    /**
     * Respond to overall resource remarks
     */
    public function respondToOverallRemarks(Request $request, Resource $resource)
    {
        if ($resource->student_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'student_response_to_remarks' => 'required|string',
        ]);

        $resource->update($validated);

        Notification::notifyMessage(
            user: $resource->assigned_by,
            title: Auth::user()->name . ' responded to overall remark',
            message: $resource->title,
            actionUrl: route('resource.show', $resource->id)
        );

        activity()
            ->performedOn($resource)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $resource->student_id])
            ->log(Auth::user()->name . ' responded to overall remark');

        return redirect()->route('resource.view', $resource)
            ->with('success', 'Overall response submitted successfully!');
    }
}
