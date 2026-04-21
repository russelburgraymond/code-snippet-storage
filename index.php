<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";
require_once __DIR__ . "/functions.php";

$pageTitle = "Snippet Storage";

$q = trim($_GET['q'] ?? '');
$language = trim($_GET['language'] ?? '');

$sql = "SELECT id, title, language, tags, description, created_at, updated_at
        FROM snippets
        WHERE 1=1";
$params = [];
$types = "";

if ($q !== '') {
    $sql .= " AND (title LIKE ? OR tags LIKE ? OR description LIKE ? OR code LIKE ?)";
    $like = "%{$q}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "ssss";
}

if ($language !== '') {
    $sql .= " AND language = ?";
    $params[] = $language;
    $types .= "s";
}

$sql .= " ORDER BY updated_at DESC, id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$languages = snippet_languages();

require_once __DIR__ . "/header.php";
?>

<section class="hero-card">
    <div class="hero-text">
        <h2>Your searchable code library</h2>
        <p>Store snippets, browse them fast, and keep your best code easy to reuse.</p>
    </div>
    <div class="hero-actions">
        <a class="btn btn-primary" href="newsnippet.php">+ New Snippet</a>
    </div>
</section>

<section class="panel">
    <form method="get" class="search-grid">
        <div class="field field-grow">
            <label for="q">Search</label>
            <input
                type="text"
                id="q"
                name="q"
                value="<?= h($q) ?>"
                placeholder="Search title, tags, description, or code"
            >
        </div>

        <div class="field">
            <label for="language">Language</label>
            <select id="language" name="language">
                <option value="">All</option>
                <?php foreach ($languages as $lang): ?>
                    <?php if ($lang === "") continue; ?>
                    <option value="<?= h($lang) ?>" <?= selected($language, $lang) ?>>
                        <?= h($lang) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field action-field">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-full">Search</button>
        </div>
    </form>
</section>

<section class="panel">
    <div class="panel-header">
        <h2>All Snippets</h2>
        <div class="panel-subtext">
            <?= (int)$result->num_rows ?> snippet<?= $result->num_rows === 1 ? '' : 's' ?> found
        </div>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="empty-state">
            <div class="empty-icon">{ }</div>
            <h3>No snippets found</h3>
            <p>Try a different search or add your first snippet.</p>
            <a class="btn btn-primary" href="newsnippet.php">Add Snippet</a>
        </div>
    <?php else: ?>
        <div class="snippet-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <article class="snippet-card">
                    <div class="snippet-card-top">
                        <div class="pill"><?= h($row['language'] ?: 'Unknown') ?></div>
                        <div class="snippet-date">Updated <?= h($row['updated_at']) ?></div>
                    </div>

                    <h3 class="snippet-title">
                        <a href="viewsnippet.php?id=<?= (int)$row['id'] ?>">
                            <?= h($row['title']) ?>
                        </a>
                    </h3>

                    <div class="snippet-tags">
                        <?php
                        $tagsArray = array_filter(array_map('trim', explode(',', (string)$row['tags'])));
                        if (!empty($tagsArray)):
                            foreach ($tagsArray as $tag):
                        ?>
                            <span class="tag-chip"><?= h($tag) ?></span>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <span class="tag-chip muted">No tags</span>
                        <?php endif; ?>
                    </div>

                    <p class="snippet-description">
                        <?php
                        $desc = trim((string)($row['description'] ?? ''));
                        echo $desc === '' ? 'No description added.' : h(mb_strimwidth($desc, 0, 180, '...'));
                        ?>
                    </p>

                    <div class="snippet-actions">
                        <a class="btn btn-secondary" href="viewsnippet.php?id=<?= (int)$row['id'] ?>">View</a>
                        <a class="btn btn-secondary" href="editsnippet.php?id=<?= (int)$row['id'] ?>">Edit</a>
                        <a class="btn btn-danger" href="deletesnippet.php?id=<?= (int)$row['id'] ?>" onclick="return confirm('Delete this snippet?');">Delete</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . "/footer.php"; ?>
