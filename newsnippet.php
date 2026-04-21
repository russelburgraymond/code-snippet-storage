<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";
require_once __DIR__ . "/functions.php";

$pageTitle = "Add Snippet";
$error = "";

$title = "";
$language = "PHP";
$tags = "";
$description = "";
$code = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $language = trim($_POST['language'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $code = trim($_POST['code'] ?? '');

    if ($title === '' || $code === '') {
        $error = "Title and code are required.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO snippets (title, language, tags, description, code)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $title, $language, $tags, $description, $code);
        $stmt->execute();

        header("Location: viewsnippet.php?id=" . (int)$conn->insert_id);
        exit;
    }
}

$languages = snippet_languages();
require_once __DIR__ . "/header.php";
?>

<section class="panel form-panel">
    <div class="panel-header">
        <h2>Add Snippet</h2>
        <div class="panel-subtext">Save reusable code for later</div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" class="stack-form">
        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= h($title) ?>" required>
        </div>

        <div class="field">
            <label for="language">Language</label>
            <select id="language" name="language">
                <?php foreach ($languages as $lang): ?>
                    <?php if ($lang === "") continue; ?>
                    <option value="<?= h($lang) ?>" <?= selected($language, $lang) ?>><?= h($lang) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label for="tags">Tags</label>
            <input type="text" id="tags" name="tags" value="<?= h($tags) ?>" placeholder="php, mysql, upload, api">
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= h($description) ?></textarea>
        </div>

        <div class="field">
            <label for="code">Code</label>
            <textarea id="code" name="code" rows="18" class="codebox" required><?= h($code) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Snippet</button>
            <a class="btn btn-secondary" href="index.php">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . "/footer.php"; ?>
