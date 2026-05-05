<?php
namespace App\Services;

use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\Question;

class InterestReportServices
{
    public function interestReport(string $formId, $request)
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
        if (isset(($report->responses))) {
            foreach ($report->responses as $item) {
                if (isset($item['question']['section']) && $item['question']['section']['step_number'] === 1) {
                    $candidateDetails[] = $item['option']['option_text'] ?? $item['answer'] ?? "";
                }

                if (isset($item['question']['section']) && $item['question']['section']['step_number'] === 2) {
                    $results['interests'][] = [
                        "interestQuestion" => $item["question"]["question_text"] ?? "",
                        "interestAnswere" => $item['option']['option_text'] ?? $item['answer'] ?? ""
                    ];
                }

                if (in_array($item['question']['question_text'], config('interests')['variableR'])) {
                    $results['variableR'][] = $item['option']['option_text'];
                } elseif (in_array($item['question']['question_text'], config('interests')['variableA'])) {
                    $results['variableA'][] = $item['option']['option_text'];
                } elseif (in_array($item['question']['question_text'], config('interests')['variableI'])) {
                    $results['variableI'][] = $item['option']['option_text'];
                } elseif (in_array($item['question']['question_text'], config('interests')['variableS'])) {
                    $results['variableS'][] = $item['option']['option_text'];
                } elseif (in_array($item['question']['question_text'], config('interests')['variableE'])) {
                    $results['variableE'][] = $item['option']['option_text'];
                } elseif (in_array($item['question']['question_text'], config('interests')['variableC'])) {
                    $results['variableC'][] = $item['option']['option_text'];
                }
            }
        }
        $results['candidate'] = $candidateDetails ?? [];
        $results['variableRTotal'] = $this->getLabel($results['variableR']);
        $results['variableATotal'] = $this->getLabel($results['variableA']);
        $results['variableITotal'] = $this->getLabel($results['variableI']);
        $results['variableSTotal'] = $this->getLabel($results['variableS']);
        $results['variableETotal'] = $this->getLabel($results['variableE']);
        $results['variableCTotal'] = $this->getLabel($results['variableC']);
        $combineResult =  $this->raisecCombination($results);
        return [
            $report,
            $results,
            $combineResult
        ];
    }

    public function getLabel($data)
    {
        $totalScore = 0;
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i] === "Agree") {
                $totalScore += 1;
            } else if ($data[$i] === "Disagree") {
                $totalScore += 0;
            }
        }
        return $totalScore;
    }

    public function raisecCombination($data){

        $scoresArray = [
            ["label" => "R", "score" => $data["variableRTotal"]],
            ["label" => "A", "score" => $data["variableATotal"]],
            ["label" => "I", "score" => $data["variableITotal"]],
            ["label" => "S", "score" => $data["variableSTotal"]],
            ["label" => "E", "score" => $data["variableETotal"]],
            ["label" => "C", "score" => $data["variableCTotal"]],
        ];

        // Sort in descending order based on score
        usort($scoresArray, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Create an array to store outcomes
        $outcomesArray = [];

        // Generate all combinations of 3 labels
        for ($i = 0; $i < count($scoresArray); $i++) {
            for ($j = $i + 1; $j < count($scoresArray); $j++) {
                for ($k = $j + 1; $k < count($scoresArray); $k++) {
                    $outcome = $scoresArray[$i]['label'] . 
                            $scoresArray[$j]['label'] . 
                            $scoresArray[$k]['label'];
                    $outcomesArray[] = $outcome;
                }
            }
        }

        // Get first 3 outcomes and convert to comma-separated string
        $threeLetterCodes = implode(',', array_slice($outcomesArray, 0, 3));

        // Calculate the total of all values in the Analysis sheet
        $totalValues = $data["variableRTotal"]+$data["variableATotal"]+$data["variableITotal"]+$data["variableSTotal"]+$data["variableETotal"]+$data["variableCTotal"];

        return [
            "threeLetterCodes" => $threeLetterCodes, 
            "totalValues" => $totalValues
        ];  
    }

    public function getRaisecData($raisec = ""){
        $data = [
            'R' => [
                'R',
                'Realistic',
                'DOERS',
                'Practical, hands-on, Tangible work',
                'Prefers' => 'hands-on, practical work involving physical activities and using tools or machinery, often in outdoor or structured environments.',
                'Values' =>  'practicality and like to see immediate results from their work.',
                'Sees-self-as' => 'practical, mechanical, and realistic.'
            ],
            'S' => [
                'S',
                'Social',
                'HELPERS', 
                'Helping, Empathetic, Friendly',
                'Prefers' => 'Enjoys working with other people as being part of a team. You believe in helping, teaching, and supporting others through direct interaction.',
                'Values' => 'Empathy, collaboration, and making a positive impact on peoples lives through service and communication.',
                'Sees-self-as' => 'Helpful, Friendly, and Trustworthy.'
            ],
            'I' => [
                'I',
                'Investigative',
                'THINKERS',
                'Research-Oriented, Knowledgeable, Curious',
                'Prefers' => 'Analytical and intellectual tasks, such as researching, solving complex problems, and working with data.',
                'Values' =>  'Critical thinking, exploring theories, and gaining deeper understanding through investigation and analysis.',
                'Sees-self-as' =>  'Precise, scientific, and intellectual.'
            ],
            'E' => [
                'E',
                'Enterprising',
                'PERSUADERS',
                'Influential, Leader, Careful',
                'Prefers' => 'Leadership and influencing others, such as managing projects, selling ideas, and driving business initiatives.',
                'Values' => 'Strategic planning, and achieving goals through persuasion and decision-making.',
                'Sees-self-as' =>   'Energetic, ambitious, and sociable.'
            ],
            'A' => [
                'A',
                'Artistic',
                'CREATORS',
                'Expressive, Creative, Visual',
                'Prefers' => 'Creative and expressive activities such as designing, performing, and creating art. (And are good at thinking outside the box and coming up with unique ideas.',
                'Values' => 'Originality, self-expression, and working in environments that allow for imaginative and innovative thinking.',
                'Sees-self-as' => 'Expressive, original, and independent.'

            ],
            'C' => [
                'C',
                'Conventional',
                'ORGANIZERS',
                'Structured, Organized, Careful',
                'Prefers' => 'Organized and detail-oriented tasks, such as managing data, maintaining records, and following established procedures.',
                'Values' => 'Accuracy, reliability, and working in structured environments with clear guidelines.',
                'Sees-self-as' => 'Orderly, and good at following a set plan.'
            ]
        ];

        return $data[$raisec];
    }
}