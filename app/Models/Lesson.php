<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Lesson
{
    public static function byCourse(int $courseId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_number ASC'
        );
        $stmt->execute([$courseId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM lessons WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByCourseAndNumber(int $courseId, int $lessonNumber): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM lessons WHERE course_id = ? AND lesson_number = ?'
        );
        $stmt->execute([$courseId, $lessonNumber]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO lessons (course_id, title, lesson_number) VALUES (?, ?, ?)'
        );
        $stmt->execute([$data['course_id'], $data['title'], $data['lesson_number']]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE lessons SET course_id = ?, title = ?, lesson_number = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['course_id'],
            $data['title'],
            $data['lesson_number'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM lessons WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function countItems(int $lessonId): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM lesson_items WHERE lesson_id = ?'
        );
        $stmt->execute([$lessonId]);
        return (int) $stmt->fetchColumn();
    }
}
