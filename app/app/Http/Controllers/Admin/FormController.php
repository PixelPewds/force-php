<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Section;
use Illuminate\Http\Request;
use DB;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::with('sections')->latest()->paginate(10);
        return view('admin.forms.list', compact('forms'));
    }
    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'visibility' => 'boolean'
        ]);

        DB::beginTransaction();
        try {

            $path = null;
            if ($request->hasFile('form_image')) {
                $path = $request->file('form_image')->store('forms', 'public');
            }

            $form = Form::create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'published',
                'created_by' => auth()->id(),
                'form_image' => $path,
                'visibility' => $request->visibility === 'on' ? 1 : 0
            ]);

            if ($request->sections) {
                foreach ($request->sections as $section) {
                    Section::create([
                        'form_id' => $form->id,
                        'name' => $section['name'],
                        'step_number' => $section['step_number'],
                        'description' => $section['description'] ?? null,
                        'created_by' => auth()->id()
                    ]);
                }
            }
            DB::commit();

            activity()
                ->performedOn($form)
                ->causedBy(auth()->user())
                ->withProperties(['id' => $form->id])
                ->log('Form created successfully');

            return redirect()
                ->route('forms.index')
                ->with('success', 'Form created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

    }
    public function edit(Form $form)
    {
        $form->load('sections');
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title' => 'required',
            'sections.*.name' => 'required',
        ]);

        if ($request->hasFile('form_image')) {
            $path = $request->file('form_image')->store('forms', 'public');
            $form->update(['form_image' => $path]);
        }

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'updated_by' => auth()->id(),
            'visibility' => $request->visibility === 'on' ? 1 : 0
        ]);

        $existingIds = $form->sections()->pluck('id')->toArray();
        $incomingIds = collect($request->sections)
            ->pluck('id')
            ->filter()
            ->toArray();

        $deleteIds = array_diff($existingIds, $incomingIds);
        Section::destroy($deleteIds);
        if ($request->sections) {
            foreach ($request->sections as $section) {
                if (isset($section['id'])) {
                    Section::where('id', $section['id'])->update([
                        'name' => $section['name'],
                        'step_number' => $section['step_number'],
                        'description' => $section['description'],
                        'updated_by' => auth()->id()
                    ]);
                } else {
                    Section::create([
                        'form_id' => $form->id,
                        'name' => $section['name'],
                        'step_number' => $section['step_number'],
                        'description' => $section['description'],
                        'created_by' => auth()->id()
                    ]);
                }
            }
        }

        activity()
            ->performedOn($form)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $form->id])
            ->log('Form updated successfully');

        return redirect()->route('forms.index')
            ->with('success', 'Form updated successfully');
    }
    public function destroy(Form $form)
    {
        activity()
            ->performedOn($form)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $form->id])
            ->log('Form deleted successfully');

        $form->delete();
        return redirect()
            ->route('forms.index')
            ->with('success', 'Form deleted successfully');
    }

}