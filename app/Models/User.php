<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(string $name, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = Database::getConnection()->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $hash, 'student']);
        return (int) Database::getConnection()->lastInsertId();
    }

    public static function allStudents(): array
    {
        $stmt = Database::getConnection()->query(
            "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::getConnection()->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
