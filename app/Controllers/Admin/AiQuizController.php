<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Services\OpenRouterService;

class AiQuizController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function generate(): void
    {
        if (!verify_csrf()) {
            $this->json(['success' => false, 'message' => 'CSRF verification failed.'], 403);
        }

        $topic = trim($_POST['topic'] ?? '');
        $numQuestions = (int) ($_POST['num_questions'] ?? 3);
        $apiKey = trim($_POST['api_key'] ?? '');

        if (empty($topic)) {
            $this->json(['success' => false, 'message' => 'Topic/Lesson prompt is required.'], 400);
        }

        $numQuestions = max(1, min(10, $numQuestions));

        try {
            $quizzes = OpenRouterService::generateQuizzes($topic, $numQuestions, $apiKey);
            $this->json([
                'success' => true,
                'message' => 'Successfully generated ' . count($quizzes) . ' quiz questions.',
                'quizzes' => $quizzes,
            ]);
        } catch (\Throwable $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
