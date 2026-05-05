<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Mail;

class ServiceHelper
{
    public static function getRaisecData($raisec = ""){
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

    public static function getStatusLabelColor($status): string
    {
        return match ($status) {
            1 => 'red',
            2 => 'blue',
            3 => 'orange',
            4 => 'green',
            default => 'black',
        };
    }

    public static function convertNumberToWords($number) {
        $no = floor($number);
        $decimal = round($number - $no, 2) * 100;
        $words = array(
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
            20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        $result = array();
        $i = 0;
        while ($no > 0) {
            $divider = ($i == 2) ? 10 : 100;
            $numberPart = $no % $divider;
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($numberPart) {
                $plural = (($counter = count($result)) && $numberPart > 9) ? 's' : null;
                $hundred = ($counter == 1 && $result[0]) ? ' and ' : null;
                $result[] = ($numberPart < 21) ? $words[$numberPart] . " " . $digits[$counter] . $plural . " " . $hundred
                    : $words[floor($numberPart / 10) * 10] . " " . $words[$numberPart % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $result[] = null;
            }
        }
        $result = array_reverse($result);
        $result = implode('', $result);
        $points = ($decimal) ? "." . $words[$decimal / 10] . " " . $words[$decimal % 10] : '';
        return trim($result) . "Rupees" . ($points ? " and " . trim($points) . " Paise" : "") . " Only";
    }
}