<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Progress
{
    public static function get(int $userId, int $lessonId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM user_progress WHERE user_id = ? AND lesson_id = ?'
        );
        $stmt->execute([$userId, $lessonId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function isItemCompleted(int $userId, int $lessonItemId): bool
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT quiz_passed FROM user_item_progress WHERE user_id = ? AND lesson_item_id = ?'
        );
        $stmt->execute([$userId, $lessonItemId]);
        $row = $stmt->fetch();
        return $row !== false && (bool) $row['quiz_passed'];
    }

    public static function markItemComplete(int $userId, int $lessonItemId): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO user_item_progress (user_id, lesson_item_id, quiz_passed)
             VALUES (?, ?, 1)
             ON DUPLICATE KEY UPDATE quiz_passed = 1, completed_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([$userId, $lessonItemId]);
    }

    public static function updateLessonProgress(int $userId, int $lessonId, int $completedCount, bool $isComplete): void
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO user_progress (user_id, lesson_id, completed_items_count, is_lesson_completed)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE completed_items_count = ?, is_lesson_completed = ?'
        );
        $isCompleteInt = $isComplete ? 1 : 0;
        $stmt->execute([
            $userId, $lessonId, $completedCount, $isCompleteInt,
            $completedCount, $isCompleteInt,
        ]);
    }

    public static function isLessonCompleted(int $userId, int $lessonId): bool
    {
        $progress = self::get($userId, $lessonId);
        return $progress !== null && (bool) $progress['is_lesson_completed'];
    }

    /**
     * Determine if a lesson is unlocked for the user.
     * Intro (lesson_number 0) is always unlocked once enrolled.
     * Lesson N unlocks when lesson N-1 is completed.
     */
    public static function isLessonUnlocked(int $userId, array $lesson, array $allLessons): bool
    {
        if ((int) $lesson['lesson_number'] === 0) {
            return true;
        }

        $prevNumber = (int) $lesson['lesson_number'] - 1;
        foreach ($allLessons as $l) {
            if ((int) $l['lesson_number'] === $prevNumber) {
                return self::isLessonCompleted($userId, (int) $l['id']);
            }
        }

        return false;
    }

    public static function getCurrentLessonForUser(int $userId, int $courseId): ?array
    {
        $lessons = Lesson::byCourse($courseId);

        foreach ($lessons as $lesson) {
            if (!self::isLessonCompleted($userId, (int) $lesson['id'])) {
                return $lesson;
            }
        }

        return end($lessons) ?: null;
    }

    public static function studentProgressSummary(int $userId): array
    {
        $sql = 'SELECT c.title AS course_title, l.title AS lesson_title,
                       l.lesson_number, up.is_lesson_completed, up.completed_items_count
                FROM user_progress up
                JOIN lessons l ON l.id = up.lesson_id
                JOIN courses c ON c.id = l.course_id
                WHERE up.user_id = ?
                ORDER BY c.title, l.lesson_number';

        $stmt = Database::getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function allStudentProgress(): array
    {
        $sql = 'SELECT u.id AS user_id, u.name, u.email,
                       c.id AS course_id, c.title AS course_title,
                       l.lesson_number, l.title AS lesson_title,
                       up.is_lesson_completed, up.completed_items_count
                FROM users u
                LEFT JOIN course_enrollments ce ON ce.user_id = u.id AND ce.status = \'approved\'
                LEFT JOIN courses c ON c.id = ce.course_id
                LEFT JOIN lessons l ON l.course_id = c.id
                LEFT JOIN user_progress up ON up.user_id = u.id AND up.lesson_id = l.id
                WHERE u.role = \'student\'
                ORDER BY u.name, c.title, l.lesson_number';

        return Database::getConnection()->query($sql)->fetchAll();
    }
}
