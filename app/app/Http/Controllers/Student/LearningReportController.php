<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\LearningReportServices;
use Illuminate\Http\Request;
use App\Models\FormSubmission;

class LearningReportController extends Controller
{
    protected $repotService;
    public function __construct(LearningReportServices $repotService)
    {
        $this->repotService = $repotService;
    }

    public function show(string $formId, Request $request)
    {
        $data = $this->repotService->learningReport($formId, $request);
        $report = $data[0];
        $results = $data[1];
        return view('students.reports.learning-report', compact('report', 'results'));
    }
}