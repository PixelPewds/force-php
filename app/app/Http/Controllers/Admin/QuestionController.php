<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Section;
use App\Models\User;
use App\Models\Question;
use App\Models\QuestionOption;
use Auth;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function index()
    {
        $forms = Form::with('sections.questions.options')->latest()->get();
        return view('admin.questions.list', compact('forms'));
    }
    public function create(Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $form = Form::findOrFail($section->form_id);
        return view('admin.questions.create', compact('section', 'form'));
    }


    public function show($section_id)
    {
        $section = Section::with('form')->with('questions.options')->findOrFail($section_id);
        return view('admin.questions.questions-list', compact('section'));
    }


    public function store(Request $request)
    {
        foreach ($request->questions as $key => $question) {
            $path = null;

            if ($request->hasFile("questions.$key.q_image")) {
                $path = $request->file("questions.$key.q_image")
                    ->store('questions', 'public');
            }

            $q = Question::create([
                'section_id' => $request->section_id,
                'form_id' => $request->form_id,
                'question_text' => $question['question_text'],
                'type' => $question['type'],
                'is_required' => isset($question['is_required']),
                'has_image' => $path,
                'range_number' => isset($question['range_number']) ? $question['range_number'] : 5,
                'created_by' => auth()->id()
            ]);

            if (isset($question['options'])) {
                foreach ($question['options'] as $option) {
                    QuestionOption::create([
                        'question_id' => $q->id,
                        'option_text' => $option,
                        'created_by' => auth()->id()
                    ]);
                }
            }
        }

        activity()
            ->performedOn($q)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $q->id])
            ->log('Questions added');

        return redirect()->route('questions.index')->with('success', 'Questions added');
    }


    public function edit($id)
    {
        $question = Question::with('options')
            ->findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    public function showForm($id)
    {
        $submission = [];
        $form = Form::with('sections.questions.options')->findOrFail($id);
        return view('admin.questions.assessment-view', compact('form', 'submission'));
    }

    public function getForms()
    {
        $forms = Form::with('sections.questions.options')->latest()->get();
        return view('admin.questions.assessment', compact('forms'));
    }


    public function update(Request $request)
    {
        // return $request->all();
        // $request->validate([
        //     'question_id' => 'required',
        //     'question_text' => 'required',
        //     'type' => 'required'
        // ]);
        $q = Question::find($request->question_id);
        if ($request->hasFile('q_image')) {
            $path = $request->file('q_image')->store('questions', 'public');
            $q->update(['has_image' => $path]);
        }

        if (isset($request->question_id)) {
            $q->update([
                'question_text' => $request->questions['question_text'],
                'type' => $request->questions['type'],
                'is_required' => isset($request->questions['is_required']),
                'updated_by' => auth()->id(),
                'range_number' => $request->questions['range_number'] ?? 5
            ]);
        }

        $existingIds = $q->options()->pluck('id')->toArray();
        $incomingIds = [];

        if (isset($request->options)) {
            foreach ($request->options as $key => $option) {
                $q = QuestionOption::find($key);
                $incomingIds[] = $key;
                $q->update([
                    'option_text' => $option,
                    'updated_by' => auth()->id()
                ]);
            }
        }
        $deleteIds = array_diff($existingIds, $incomingIds);
        QuestionOption::destroy($deleteIds);

        if (isset($request->new_options)) {
            foreach ($request->new_options as $option) {
                QuestionOption::create([
                    'question_id' => $request->question_id,
                    'option_text' => $option,
                    'created_by' => auth()->id()
                ]);
            }
        }

        activity()
            ->performedOn($q)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $q->id])
            ->log('Questions updated');

        return back()->with('success', 'Questions updated');
    }


    public function destroy($id)
    {
        $q = Question::destroy($id);
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['id' => $id])
            ->log('Questions deleted');
        return back()->with('success', 'Question deleted');
    }

}