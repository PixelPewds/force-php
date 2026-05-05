<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use App\Services\SkillReportServices;

class FormReportController extends Controller
{
    protected $repotService;
    public function __construct(SkillReportServices $repotService)
    {
        $this->repotService = $repotService;
    }
    public function show($formId, Request $request)
    {
        $data = $this->repotService->skillReport($formId, $request);
        $finalSkillReport = $data[0];
        $candidateInfo = $data[1];
        $grouped = $data[2];
        return view('students.reports.skill-report', compact('finalSkillReport', 'candidateInfo', 'grouped'));
    }
}
