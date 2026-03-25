<?php

use App\Services\ScoreCalculator;

test('it calculates a perfect score', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [1 => 0, 2 => 3];
    $correctKeys = [1 => 0, 2 => 3];
    
    $score = $calculator->calculate($userAnswers, $correctKeys);
    expect($score)->toBe(100.0);
});

test('it calculates a failing score', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [1 => 1, 2 => 2];
    $correctKeys = [1 => 0, 2 => 3];
    
    $score = $calculator->calculate($userAnswers, $correctKeys);
    expect($score)->toBe(0.0);
});

test('it calculates a partial score', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [1 => 0, 2 => 2];
    $correctKeys = [1 => 0, 2 => 3];
    
    $score = $calculator->calculate($userAnswers, $correctKeys);
    expect($score)->toBe(50.0);
});

test('it returns zero for empty answers', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [];
    $correctKeys = [1 => 0, 2 => 3];
    
    $score = $calculator->calculate($userAnswers, $correctKeys);
    expect($score)->toBe(0.0);
});

test('it throws exception for empty correct keys', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [1 => 0];
    $correctKeys = [];
    
    expect(fn() => $calculator->calculate($userAnswers, $correctKeys))
        ->toThrow(\InvalidArgumentException::class);
});

test('it provides detailed scoring breakdown', function () {
    $calculator = new ScoreCalculator();
    $userAnswers = [1 => 0, 2 => 2];
    $correctKeys = [1 => 0, 2 => 3];
    
    $breakdown = $calculator->getDetailedScore($userAnswers, $correctKeys);
    
    expect($breakdown['total_questions'])->toBe(2);
    expect($breakdown['correct_answers'])->toBe(1);
    expect($breakdown['percentage'])->toBe(50.0);
});
