<?php
namespace App\Services;

use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\Question;

class LearningReportServices
{
    public function learningReport($formId, $request)
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

        foreach ($report->responses as $item) {
            if ($item['question']['type'] == 'checkbox') {
                $results['Learning'] = $item->checkbox_options;
            }

            if (isset($item['question']['section']) && $item['question']['section']['step_number'] === 1) {
                $candidateDetails[] = $item['option']['option_text'] ?? $item['answer'] ?? "";
            }
        }
        $results['candidate'] = $candidateDetails ?? [];

        $activisttotal = $this->countMatchingNumbers($results['Learning'], config('learning')['activistNumbers']);
        $reflectortotal = $this->countMatchingNumbers($results['Learning'], config('learning')['reflectorNumbers']);
        $theoristtotal = $this->countMatchingNumbers($results['Learning'], config('learning')['theoristNumbers']);
        $pragmatisttotal = $this->countMatchingNumbers($results['Learning'], config('learning')['pragmatistNumbers']);

        $results['Activist'] = [
            $activisttotal,
            $this->getLabel($activisttotal, 'activist')
        ];
        $results['Reflector'] = [
            $reflectortotal,
            $this->getLabel($reflectortotal, 'reflector')
        ];
        $results['Theorist'] = [
            $theoristtotal,
            $this->getLabel($theoristtotal, 'theorist')
        ];
        $results['Pragmatist'] = [
            $pragmatisttotal,
            $this->getLabel($pragmatisttotal, 'pragmatist')
        ];
        return [$report, $results];
    }

    public function countMatchingnumbers($numbers, $targetnumbers)
    {
        $count = 0;
        for ($i = 0; $i < count($numbers); $i++) {
            if (in_array($numbers[$i], $targetnumbers)) {
                $count++;
            }
        }
        return $count;
    }

    public function getLabel($total, $category)
    {
        if ($category === 'activist') {
            if ($total > 12) {
                return 'Very Strong';
            } else if ($total > 10) {
                return 'Strong';
            } else if ($total > 6) {
                return 'Moderate';
            } else if ($total > 3) {
                return 'Low';
            } else {
                return 'Very Low';
            }
        } else if ($category === 'reflector') {
            if ($total > 17) {
                return 'Very Strong';
            } else if ($total > 14) {
                return 'Strong';
            } else if ($total > 11) {
                return 'Moderate';
            } else if ($total > 8) {
                return 'Low';
            } else {
                return 'Very Low';
            }
        } else if ($category === 'theorist') {
            if ($total > 15) {
                return 'Very Strong';
            } else if ($total > 13) {
                return 'Strong';
            } else if ($total > 10) {
                return 'Moderate';
            } else if ($total > 7) {
                return 'Low';
            } else {
                return 'Very Low';
            }
        } else if ($category === 'pragmatist') {
            if ($total > 16) {
                return 'Very Strong';
            } else if ($total > 14) {
                return 'Strong';
            } else if ($total > 11) {
                return 'Moderate';
            } else if ($total > 8) {
                return 'Low';
            } else {
                return 'Very Low';
            }
        }
    }
}