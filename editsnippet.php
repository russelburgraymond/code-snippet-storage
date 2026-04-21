<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";
require_once __DIR__ . "/functions.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM snippets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$snippet = $stmt->get_result()->fetch_assoc();

if (!$snippet) {
    $pageTitle = "Snippet Not Found";
    require_once __DIR__ . "/header.php";
    ?>
    <section class="panel">
        <div class="empty-state">
            <div class="empty-icon">?</div>
            <h3>Snippet not found</h3>
            <a class="btn btn-secondary" href="index.php">Back</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . "/footer.php";
    exit;
}

$error = "";

$title = $snippet['title'];
$language = $snippet['language'];
$tags = $snippet['tags'];
$description = $snippet['description'];
$code = $snippet['code'];

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
            UPDATE snippets
            SET title = ?, language = ?, tags = ?, description = ?, code = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssssi", $title, $language, $tags, $description, $code, $id);
        $stmt->execute();

        header("Location: viewsnippet.php?id=" . (int)$id);
        exit;
    }
}

$pageTitle = "Edit Snippet";
$languages = snippet_languages();
require_once __DIR__ . "/header.php";
?>

<section class="panel form-panel">
    <div class="panel-header">
        <h2>Edit Snippet</h2>
        <div class="panel-subtext">Update your saved code</div>
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
            <input type="text" id="tags" name="tags" value="<?= h($tags) ?>">
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
            <button type="submit" class="btn btn-primary">Update Snippet</button>
            <a class="btn btn-secondary" href="viewsnippet.php?id=<?= (int)$id ?>">Cancel</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . "/footer.php"; ?>
