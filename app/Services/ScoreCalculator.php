<?php

namespace App\Services;

class ScoreCalculator
{
    /**
     * Calculate quiz score by comparing user answers against correct keys
     *
     * @param array $userAnswers Associative array of question_id => user_answer
     * @param array $correctKeys Associative array of question_id => correct_answer
     * @return float Percentage score (0-100)
     * @throws \InvalidArgumentException
     */
    public function calculate(array $userAnswers, array $correctKeys): float
    {
        if (empty($correctKeys)) {
            throw new \InvalidArgumentException('Correct keys cannot be empty');
        }

        if (empty($userAnswers)) {
            return 0.0;
        }

        $totalQuestions = count($correctKeys);
        $correctAnswers = 0;

        foreach ($correctKeys as $questionId => $correctAnswer) {
            if (isset($userAnswers[$questionId])) {
                if ($userAnswers[$questionId] === $correctAnswer || 
                    (int)$userAnswers[$questionId] === (int)$correctAnswer) {
                    $correctAnswers++;
                }
            }
        }

        $percentage = ($correctAnswers / $totalQuestions) * 100;
        return round($percentage, 2);
    }

    /**
     * Validate answer structure
     *
     * @param array $answers
     * @return bool
     */
    public function validate(array $answers): bool
    {
        return !empty($answers) && is_array($answers);
    }

    /**
     * Get detailed scoring breakdown
     *
     * @param array $userAnswers
     * @param array $correctKeys
     * @return array
     */
    public function getDetailedScore(array $userAnswers, array $correctKeys): array
    {
        $totalQuestions = count($correctKeys);
        $correctAnswers = 0;
        $breakdown = [];

        foreach ($correctKeys as $questionId => $correctAnswer) {
            $userAnswer = $userAnswers[$questionId] ?? null;
            $isCorrect = $userAnswer === $correctAnswer || (int)$userAnswer === (int)$correctAnswer;
            
            if ($isCorrect) {
                $correctAnswers++;
            }

            $breakdown[$questionId] = [
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        return [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'percentage' => round(($correctAnswers / $totalQuestions) * 100, 2),
            'breakdown' => $breakdown,
        ];
    }
}
