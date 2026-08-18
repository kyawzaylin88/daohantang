<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Quiz
{
    public static function byLessonItem(int $lessonItemId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM quizzes WHERE lesson_item_id = ? ORDER BY id ASC'
        );
        $stmt->execute([$lessonItemId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM quizzes WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO quizzes (lesson_item_id, question, option_a, option_b, option_c, option_d, correct_option)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['lesson_item_id'],
            $data['question'],
            $data['option_a'],
            $data['option_b'],
            $data['option_c'],
            $data['option_d'],
            $data['correct_option'],
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE quizzes SET lesson_item_id = ?, question = ?, option_a = ?, option_b = ?,
             option_c = ?, option_d = ?, correct_option = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['lesson_item_id'],
            $data['question'],
            $data['option_a'],
            $data['option_b'],
            $data['option_c'],
            $data['option_d'],
            $data['correct_option'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM quizzes WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function deleteByLessonItem(int $lessonItemId): void
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM quizzes WHERE lesson_item_id = ?');
        $stmt->execute([$lessonItemId]);
    }
}
