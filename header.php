<?php
if (!isset($pageTitle)) {
    $pageTitle = "Snippet Storage";
}
require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/version.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-bg"></div>

<div class="container">
    <header class="site-header">
        <div class="brand-wrap">
            <div class="brand-badge">&lt;/&gt;</div>
            <div>
<h1 class="site-title"><?= APP_NAME ?></h1>
<p class="site-subtitle">Version <?= APP_VERSION ?></p>
            </div>
        </div>

        <nav class="main-nav">
            <a href="index.php">Home</a>
            <a href="newsnippet.php">Add Snippet</a>
        </nav>
    </header>

    <main class="main-content">
