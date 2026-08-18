<?php

/**
 * One-time setup script.
 * Run via browser: http://localhost/daohantang/setup.php
 * DELETE this file after setup in production.
 */

declare(strict_types=1);

$config = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    foreach ($statements as $sql) {
        if ($sql !== '') {
            $pdo->exec($sql);
        }
    }

    // Update admin password to admin123
    $pdo->exec("USE {$config['dbname']}");
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@daohantang.com'");
    $stmt->execute([$hash]);

    // Load demo content
    $seedFile = __DIR__ . '/database/seed_demo.sql';
    if (file_exists($seedFile)) {
        $seed = file_get_contents($seedFile);
        foreach (array_filter(array_map('trim', explode(';', $seed))) as $sql) {
            if ($sql !== '') {
                $pdo->exec($sql);
            }
        }
    }

    echo '<h2>DaoHanTang Setup Complete!</h2>';
    echo '<p>Database created and seeded successfully.</p>';
    echo '<p><strong>Admin Login:</strong> admin@daohantang.com / admin123</p>';
    echo '<p><a href="index.php">Go to Homepage</a></p>';
    echo '<p style="color:red;"><strong>Important:</strong> Delete setup.php after running this script.</p>';

} catch (PDOException $e) {
    echo '<h2>Setup Failed</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
