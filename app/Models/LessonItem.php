<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class LessonItem
{
    public static function byLesson(int $lessonId): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM lesson_items WHERE lesson_id = ? ORDER BY item_order ASC'
        );
        $stmt->execute([$lessonId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM lesson_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO lesson_items (lesson_id, item_order, video_url) VALUES (?, ?, ?)'
        );
        $stmt->execute([$data['lesson_id'], $data['item_order'], $data['video_url']]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE lesson_items SET lesson_id = ?, item_order = ?, video_url = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['lesson_id'],
            $data['item_order'],
            $data['video_url'],
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM lesson_items WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
