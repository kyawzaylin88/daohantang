<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Enrollment
{
    public static function find(int $userId, int $courseId): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT * FROM course_enrollments WHERE user_id = ? AND course_id = ?'
        );
        $stmt->execute([$userId, $courseId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function request(int $userId, int $courseId): void
    {
        $existing = self::find($userId, $courseId);
        if ($existing) {
            if ($existing['status'] === 'declined') {
                $stmt = Database::getConnection()->prepare(
                    "UPDATE course_enrollments SET status = 'pending' WHERE id = ?"
                );
                $stmt->execute([$existing['id']]);
            }
            return;
        }

        $stmt = Database::getConnection()->prepare(
            'INSERT INTO course_enrollments (user_id, course_id, status) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $courseId, 'pending']);
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE course_enrollments SET status = ? WHERE id = ?'
        );
        return $stmt->execute([$status, $id]);
    }

    public static function allWithDetails(): array
    {
        $sql = 'SELECT ce.*, u.name AS user_name, u.email AS user_email,
                       c.title AS course_title
                FROM course_enrollments ce
                JOIN users u ON u.id = ce.user_id
                JOIN courses c ON c.id = ce.course_id
                ORDER BY ce.updated_at DESC';
        return Database::getConnection()->query($sql)->fetchAll();
    }

    public static function isApproved(int $userId, int $courseId): bool
    {
        $enrollment = self::find($userId, $courseId);
        return $enrollment !== null && $enrollment['status'] === 'approved';
    }
}
