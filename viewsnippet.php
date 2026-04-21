<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/schema.php";
require_once __DIR__ . "/functions.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM snippets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$snippet = $stmt->get_result()->fetch_assoc();

$pageTitle = $snippet ? $snippet['title'] : "Snippet Not Found";
require_once __DIR__ . "/header.php";
?>

<section class="panel">
    <?php if (!$snippet): ?>
        <div class="empty-state">
            <div class="empty-icon">?</div>
            <h3>Snippet not found</h3>
            <a class="btn btn-secondary" href="index.php">Back</a>
        </div>
    <?php else: ?>
        <div class="detail-header">
            <div>
                <div class="detail-pill"><?= h($snippet['language'] ?: 'Unknown') ?></div>
                <h2 class="detail-title"><?= h($snippet['title']) ?></h2>

                <div class="detail-tags">
                    <?php
                    $tagsArray = array_filter(array_map('trim', explode(',', (string)$snippet['tags'])));
                    if (!empty($tagsArray)):
                        foreach ($tagsArray as $tag):
                    ?>
                        <span class="tag-chip"><?= h($tag) ?></span>
                    <?php endforeach; else: ?>
                        <span class="tag-chip muted">No tags</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-actions">
                <a class="btn btn-secondary" href="editsnippet.php?id=<?= (int)$snippet['id'] ?>">Edit</a>
                <a class="btn btn-danger" href="deletesnippet.php?id=<?= (int)$snippet['id'] ?>" onclick="return confirm('Delete this snippet?');">Delete</a>
            </div>
        </div>

        <div class="meta-card-grid">
            <div class="meta-card">
                <div class="meta-label">Created</div>
                <div class="meta-value"><?= h($snippet['created_at']) ?></div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Updated</div>
                <div class="meta-value"><?= h($snippet['updated_at']) ?></div>
            </div>
        </div>

        <?php if (trim((string)$snippet['description']) !== ''): ?>
            <div class="description-panel">
                <h3>Description</h3>
                <p><?= nl2br(h($snippet['description'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="code-panel">
            <div class="code-panel-header">
                <h3>Code</h3>
                <button type="button" class="btn btn-primary" onclick="copyCode()">Copy Code</button>
            </div>

            <pre class="code-pre"><code id="snippetCode"><?= h($snippet['code']) ?></code></pre>
        </div>

        <div class="bottom-actions">
            <a class="btn btn-secondary" href="index.php">Back</a>
        </div>

        <script>
        function copyCode() {
            const code = document.getElementById('snippetCode').innerText;
            copyTextToClipboard(code).then(function() {
                alert('Code copied to clipboard.');
            }).catch(function() {
                alert('Unable to copy code.');
            });
        }
        </script>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . "/footer.php"; ?>
