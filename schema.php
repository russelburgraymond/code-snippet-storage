<?php
if (!isset($conn)) {
    require_once __DIR__ . "/db.php";
}

function ensure_schema(mysqli $conn): void
{
    $conn->query("
        CREATE TABLE IF NOT EXISTS snippets (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            language VARCHAR(50) NOT NULL DEFAULT '',
            tags VARCHAR(255) NOT NULL DEFAULT '',
            description TEXT NULL,
            code LONGTEXT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_language (language),
            KEY idx_title (title),
            KEY idx_updated (updated_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

ensure_schema($conn);
