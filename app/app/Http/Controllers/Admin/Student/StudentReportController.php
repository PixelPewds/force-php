<?php

namespace App\Http\Controllers\Admin\Student;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use App\Services\InterestReportServices;
use App\Services\LearningReportServices;
use App\Services\SkillReportServices;
use App\Services\ValueReportServices;
use DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class StudentReportController extends Controller
{
    protected $skillService;
    protected $interestService;
    protected $learningService;
    protected $valueReportService;

    public function __construct(SkillReportServices $skillService, InterestReportServices $interestService, LearningReportServices $learningService, ValueReportServices $valueReportService)
    {
        $this->skillService = $skillService;
        $this->interestService = $interestService;
        $this->learningService = $learningService;
        $this->valueReportService = $valueReportService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with([
            'formsubmission',
            'formsubmission.form:id,title',
        ])->role('student')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.students.assessment.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $formId = $request->input('formId');
        $studId = $request->input('studId');

        $user = User::find($studId);
        $data = [
            $this->skillService->skillReport($formId['skill'], $studId),
            $this->interestService->interestReport($formId['interests'], $studId),
            $this->learningService->learningReport($formId['learning'], $studId),
            $this->valueReportService->valueReport($formId['value'], $studId),
        ];

        $main = $data[0][0];
        $candidate = [
            'email' => $user->email??$main[0]['answer'] ?? '',
            'name' => $user->name??$main[1]['answer'] ?? '',
            'age' => $main[2]['answer'] ?? '',
            'gender' => $user->gender??$main[3]['answer'] ?? '',
            'education' => $main[4]['answer'] ?? ''
        ];

        $grouped = $data[0][2];
        $results = $data[3][1];
        $resultLearning = $data[2][1];
        $resultInterest = $data[1][1];
        $raisec = $data[1][2]??[];

        if (isset($request->final)) {
            $interests = $this->getInterests($resultInterest);
            $raisec = explode(',',$raisec['threeLetterCodes']??[]);
            $categories = ['Creativity', 'Communication', 'Leadership', 'Relationship', 'Analytical', 'Technical'];
            $totals = collect($categories)
                    ->mapWithKeys(function ($cat) use ($grouped) {
                        return [
                            $cat => collect($grouped[$cat] ?? [])->sum('score')
                        ];
                    })
                    ->sortDesc()
                    ->map(function ($score, $cat) {
                        return [$cat, $score];
                    })
                    ->values(); 

            return view('admin.students.downloads.final-report-view', compact('grouped', 'results', 'resultLearning', 'resultInterest', 'candidate','interests','raisec','totals','categories'));
        }

        $pdf = Pdf::loadView('admin.students.downloads.final-pdf', [
            'grouped' => $grouped,
            'results' => $results,
            'resultLearning' => $resultLearning,
            'resultInterest' => $resultInterest,
            'candidate' => $candidate
        ])->setOptions([
                    'margin_top' => 0,
                    'margin_bottom' => 0,
                    'margin_left' => 0,
                    'margin_right' => 0,
                ]);

        if (isset($request->view)) {
            return view('admin.students.downloads.final-pdf', compact('grouped', 'results', 'resultLearning', 'resultInterest', 'candidate','raisec'));
        }        
        return $pdf->download('report.pdf');
    }

    public function getInterests($resultInterest){
        $interestData = [];
        if($resultInterest['interests']){
          foreach ($resultInterest['interests'] as $interest){
                if(str_contains($interest['interestQuestion'],'always dreamt of becoming') ){
                    $interestData['career'] =  $interest['interestAnswere']??"Not Specified";
                }

                if(str_contains($interest['interestQuestion'],'favorite subjects') ){
                    $interestData['subjects'] = $interest['interestAnswere']??"Not Specified";
                }

                if(str_contains($interest['interestQuestion'],'favorite extracurricular') ){
                    $interestData['hobbies'] = $interest['interestAnswere']??"Not Specified";
                }
          }
        }
        return $interestData;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $reAttempt = FormSubmission::with('form:id,title,visibility')
            ->where('status', 'submitted')
            ->where('form_id', $id)
            ->where('student_id', $request->studId)
            ->first();

        $reAttempt->update([
            'status' => 'draft'
        ]);

        activity()
            ->performedOn($reAttempt)
            ->causedBy(auth()->user())
            ->withProperties(['id' => $reAttempt->student_id])
            ->log('Re-attempt enabled');


        return redirect()->back()->with('success', 'Response submitted successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $forms = Form::with([
            'formsubmissions' => function ($q) use ($id) {
                $q->where('student_id', $id);
            }
        ])->get();

        $submissions = FormSubmission::with('form:id,title,visibility')
            ->where('status', 'submitted')
            ->where('student_id', $id)
            ->get();

        $formData = [];
        $count = 0;
        foreach ($submissions as $submission) {
            if (str_contains($submission->form->title, 'Interests') || str_contains($submission->form->title, 'Interest')) {
                $formData['interests'] = $submission->form_id;
                $count++;
            } elseif (str_contains($submission->form->title, 'Values') || str_contains($submission->form->title, 'Value')) {
                $formData['value'] = $submission->form_id;
                $count++;
            } elseif (str_contains($submission->form->title, 'Learnings') || str_contains($submission->form->title, 'Learning')) {
                $formData['learning'] = $submission->form_id;
                $count++;
            } elseif (str_contains($submission->form->title, 'Skills') || str_contains($submission->form->title, 'Skill')) {
                $formData['skill'] = $submission->form_id;
                $count++;
            }
        }
        return view('admin.students.assessment.student-report', compact('forms', 'submissions', 'formData', 'count'));
    }
}
