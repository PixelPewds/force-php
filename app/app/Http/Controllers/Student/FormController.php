<?php

namespace App\Http\Controllers\Student;

use App\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Models\TaskCompletionSystem;
use Auth;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\Question;

class FormController extends Controller
{
    public function start($formId, Request $request)
    {
        $submission = FormSubmission::firstOrCreate(
            [
                'form_id' => $formId,
                'student_id' => auth()->id()
            ],
            [
                'status' => 'draft'
            ]
        );

        if ($submission->status === 'submitted') {
            $submission->load('responses');
            $this->congrats($submission);
            return view('students.congrats.congrats', compact('submission'));
        }

        $form = Form::with('sections.questions.options')->find($formId);

        if ($request->has('task_id')) {
            $task = TaskCompletionSystem::find($request->input('task_id'));
            $task->update([
                'completion_percentage' => '60'
            ]);
        }

        Notification::notifyMessage(
            user: $form->created_by,
            title: Auth::user()->name . ' attempted to complete, ' . $form->title . ' form',
            message: $form->title,
            actionUrl: null
        );

        activity()
            ->performedOn($submission)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $submission->id])
            ->log('Student Started filling the form');

        return view('students.assessment-view', compact('form', 'submission'));
    }

    public function saveDraft(Request $request)
    {
        $request->validate([
            'form_id' => 'required',
            // 'answers.*' => 'required',
            'current_step' => 'required'
        ]);

        $submission = FormSubmission::where(
            'form_id',
            $request->form_id
        )->where(
                'student_id',
                auth()->id()
            )->first();

        $questions = Question::whereIn('id', array_keys($request->answers))
            ->pluck('type', 'id');


        foreach ($request->answers as $questionId => $answer) {
            $type = $questions[$questionId] ?? null;
            $data = [
                'submission_id' => $submission->id,
                'question_id' => $questionId
            ];

            $values = [
                'answer' => null,
                'option_id' => null
            ];

            switch ($type) {
                case 'radio':
                    $values['option_id'] = $answer;
                    break;

                case 'checkbox':
                    $values['answer'] = json_encode($answer); // store as JSON
                    break;

                case 'range':
                    $values['answer'] = str_replace('range-', '', $answer);
                    break;

                case 'text':
                case 'textarea':
                case 'email':
                case 'url':
                case 'tel':
                case 'number':
                    $values['answer'] = $answer;
                    break;

                default:
                    $values['answer'] = $answer;
            }
            if (isset($values['answer']) || isset($values['option_id'])) {
                Response::updateOrCreate($data, $values);
            }
        }

        activity()
            ->performedOn($submission)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $submission->id])
            ->log('Student form saved as draft');

        return response()->json([
            'status' => 'saved'
        ]);
    }

    public function submit(Request $request)
    {
        $questions = Question::whereIn('id', array_keys($request->answers))
            ->pluck('type', 'id');

        $request->validate([
            'form_id' => 'required',
            'answers.*' => 'required',
            'current_step' => 'required'
        ]);

        $submission = FormSubmission::where(
            'form_id',
            $request->form_id
        )->where(
                'student_id',
                auth()->id()
            )->first();

        $questions = Question::whereIn('id', array_keys($request->answers))
            ->pluck('type', 'id');

        foreach ($request->answers as $questionId => $answer) {
            $type = $questions[$questionId] ?? null;
            $data = [
                'submission_id' => $submission->id,
                'question_id' => $questionId
            ];

            $values = [
                'answer' => null,
                'option_id' => null
            ];

            switch ($type) {
                case 'radio':
                    $values['option_id'] = $answer;
                    break;

                case 'checkbox':
                    $values['answer'] = json_encode($answer); // store as JSON
                    break;

                case 'range':
                    $values['answer'] = str_replace('range-', '', $answer);
                    break;

                case 'text':
                case 'textarea':
                case 'email':
                case 'url':
                case 'tel':
                case 'number':
                    $values['answer'] = $answer;
                    break;

                default:
                    $values['answer'] = $answer;
            }
            if (isset($values['answer']) || isset($values['option_id'])) {
                Response::updateOrCreate($data, $values);
            }
        }

        $submission = FormSubmission::where(
            'form_id',
            $request->form_id
        )->where(
                'student_id',
                auth()->id()
            )->first();

        $submission->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        Notification::notifyMessage(
            user: $submission->form->created_by,
            title: Auth::user()->name . ' completed, ' . $submission->form->title . ' form successfully!',
            message: $submission->form->title,
            actionUrl: null
        );

        activity()
            ->performedOn($submission)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $submission->id])
            ->log('Student submitted form');

        $this->congrats($submission);
    }

    public function congrats($submission)
    {
        return view('students.congrats.congrats', compact('submission'));
    }

    public function getForms()
    {
        $forms = Form::with([
            'sections.questions.options',
            'formSubmissions' => function ($q) {
                $q->where('student_id', auth()->id());
            }
        ])
            ->where('visibility', 1)
            ->latest()->get();
        return view('students.assessment.list', compact('forms'));
    }
}
