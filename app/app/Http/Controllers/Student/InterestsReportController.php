<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\InterestReportServices;
use Illuminate\Http\Request;
use App\Models\FormSubmission;

class InterestsReportController extends Controller
{
    protected $repotService;
    public function __construct(InterestReportServices $repotService)
    {
        $this->repotService = $repotService;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $formId, Request $request)
    {
        $data = $this->repotService->interestReport($formId, $request);
        $report = $data[0];
        $results = $data[1];
        return view('students.reports.interests-report', compact('report', 'results'));
    }

}
