<?php
namespace App\Services;

use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\Question;

class ValueReportServices
{
    public function valueReport(string $formId, $request)
    {
        $report = FormSubmission::with([
            'responses.question:id,question_text,type,section_id',
            'responses.question.section:id,step_number',
            'responses.option:id,option_text',
        ])->where('form_id', $formId)
            ->where('student_id', $request->studId ?? $request ?? auth()->id())
            ->where('status', 'submitted')
            ->first();

        $results = [];
        $candidateDetails = [];

        $mapping = config('values');
        foreach ($mapping as $value => $questions) {
            $results[$value] = 0;
        }

        foreach ($report->responses as $item) {
            if (isset($item['question']['section']) && $item['question']['section']['step_number'] === 1) {
                $candidateDetails[] = $item['option']['option_text'] ?? $item['answer'] ?? "";
            }

            if ($item['question']['type'] !== 'range') {
                if (isset($item['answer']) && (str_contains($item['answer'], ',') || str_contains($item['answer'], '.'))) {
                    $results['additional'] = $item['answer'] ?? "";
                }
                continue;
            }
            $questionText = $item['question']['question_text'];
            $score = (int) $item['answer'];
            foreach ($mapping as $value => $questions) {
                if (in_array($questionText, $questions)) {
                    $results[$value] += $score;
                    break;
                }
            }
        }
        $results['candidate'] = $candidateDetails ?? [];

        return [
            $report,
            $results
        ];
    }
}