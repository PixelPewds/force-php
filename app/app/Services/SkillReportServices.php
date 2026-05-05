<?php
namespace App\Services;

use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\Question;

class SkillReportServices
{
    public function skillReport($formId, $request)
    {
        $report = FormSubmission::with([
            'responses.question:id,question_text,type,section_id',
            'responses.question.section:id,step_number',
            'responses.option:id,option_text',
        ])->where('form_id', $formId)
            ->where('student_id', $request->studId ?? $request ?? auth()->id())
            ->where('status', 'submitted')
            ->first();

        $finalSkillReport = $report->responses
            ->groupBy(function ($response) {
                return strtolower(trim(explode(':', $response->question->question_text)[0]));
            })
            ->map(function ($responses, $skill) {
                $proficiency = null;
                $importance = null;
                $questionTxt = null;
                $categoryWiseData = [];
                foreach ($responses as $response) {
                    $answerText = optional($response->option)->option_text ?? $response->answer;
                    $questionText = $response->question->question_text;
                    if ($answerText) {
                        if (str_contains($answerText, 'Proficiency')) {
                            $proficiency = $answerText;
                        }
                        if (str_contains($answerText, 'Importance')) {
                            $questionTxt = $response->question->question_text;
                            $categoryWiseData[] = $response->question->question_text;
                            $importance = $answerText;
                        }
                    }
                }

                if ($proficiency && $importance) {
                    $score = app()->make(self::class)
                        ->calculateValue($proficiency, $importance);

                    $skillCatergory = app()->make(self::class)
                        ->getSkillCategory($questionTxt);

                    return [
                        'skill' => ucfirst($skill),
                        'proficiency' => $proficiency,
                        'importance' => $importance,
                        'score' => $score,
                        'skillSet' => $questionTxt,
                        'skillCatergory' => $skillCatergory,
                        'categoryWiseData' => $categoryWiseData
                    ];
                } else {
                    return [
                        'answer' => $answerText,
                        'questionText' => $questionText
                    ];
                }
            })
            ->values();

       $grouped = collect($finalSkillReport)
                    ->filter(fn($item) => isset($item['skillCatergory']))
                    ->groupBy('skillCatergory')
                    ->map(fn($items) => $items->sortByDesc('score')
                    ->values());
                    
        $candidateInfo = [];
        foreach ($finalSkillReport as $key => $value) {
            if (isset($value['answer']) && $value['answer']) {
                $candidateInfo[$key] = $value['answer'] ?? "---";
            }
        }
        return [
            $finalSkillReport,
            $candidateInfo,
            $grouped
        ];
    }

    public function getSkillCategory($text)
    {
        $category = config('skill-category');
        foreach ($category as $key => $value) {
            if (in_array($text, $value)) {
                return $key;
            }
        }
        return null;
    }


    public function calculateValue($proficiency, $importance)
    {
        if ($proficiency === "High Proficiency" && $importance === "High Importance") {
            return 12;
        } else if ($proficiency === "High Proficiency" && $importance === "Moderate Importance") {
            return 9;
        } else if ($proficiency === "Moderate Proficiency" && $importance === "High Importance") {
            return 8;
        } else if ($proficiency === "Moderate Proficiency" && $importance === "Moderate Importance") {
            return 6;
        } else if ($proficiency === "Moderate Proficiency" && $importance === "Low Importance") {
            return 4;
        } else {
            return 2;
        }
    }

    function getSkillName($text)
    {
        return strtolower(trim(explode(':', $text)[0]));
    }
}