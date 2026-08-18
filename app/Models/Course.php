<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Course
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->query('SELECT * FROM courses ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO courses (title, description, price, thumbnail) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['thumbnail'] ?? null,
        ]);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE courses SET title = ?, description = ?, price = ?, thumbnail = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['thumbnail'] ?? null,
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM courses WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
