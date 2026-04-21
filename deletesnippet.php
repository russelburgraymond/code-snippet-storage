<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM snippets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
