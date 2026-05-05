<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\ValueReportServices;
use Illuminate\Http\Request;
use App\Models\FormSubmission;

class ValuesReportController extends Controller
{
    protected $repotService;
    public function __construct(ValueReportServices $repotService)
    {
        $this->repotService = $repotService;
    }

    public function show(string $formId, Request $request)
    {
        $data = $this->repotService->valueReport($formId, $request);
        $report = $data[0];
        $results = $data[1];
        return view('students.reports.value-report', compact('report', 'results'));
    }
}